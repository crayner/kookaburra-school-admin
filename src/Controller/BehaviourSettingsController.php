<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 15/01/2020
 * Time: 16:04
 */

namespace Kookaburra\SchoolAdmin\Controller;


use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Entity\Setting;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Form\BehaviourSettingsType;
use Kookaburra\SchoolAdmin\Form\BehaviourSettingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class BehaviourSettingsController extends AbstractController
{
    /**
     * settings
     * @param PageManager $pageManager
     * @param ContainerManager $manager
     * @param TranslatorInterface $translator
     * @param string $tabName
     * @return JsonResponse|Response
     * @Route("/behaviour/settings/{tabName}", name="behaviour_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(PageManager $pageManager, ContainerManager $manager, TranslatorInterface $translator, string $tabName = 'Descriptors')
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        $settingProvider = ProviderFactory::create(Setting::class);
        $settingProvider->getSettingsByScope('Behaviour');
        $container = new Container();

        $form = $this->createForm(BehaviourSettingsType::class, null, ['action' => $this->generateUrl('school_admin__behaviour_settings')]);

        if ($request->getContent() !== '') {
            $data = [];
            $data['status'] = 'success';
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                $form = $this->createForm(BehaviourSettingsType::class, null, ['action' => $this->generateUrl('school_admin__behaviour_settings')]);
            } catch (\Exception $e) {
                $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $panel = new Panel('Descriptors');
        $container->setTranslationDomain('SchoolAdmin')->addForm('Descriptors', $form->createView())->addPanel($panel)->setSelectedPanel($tabName)->setTarget('formContent');
        $panel = new Panel('Levels');
        $container->addPanel($panel);
        $panel = new Panel('Letters');
        $container->addPanel($panel);
        $panel = new Panel('Miscellaneous');
        $container->addPanel($panel);

        // Finally Finished
        $manager->addContainer($container)->buildContainers();
        return $pageManager->createBreadcrumbs('Behaviour Settings', [])
            ->render(['containers' => $manager->getBuiltContainers()]);
    }
}