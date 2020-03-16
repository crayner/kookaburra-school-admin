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
 * Date: 14/01/2020
 * Time: 17:06
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Entity\Setting;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Form\MarkbookSettingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class MarkbookSettingController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class MarkbookSettingController extends AbstractController
{
    /**
     * settings
     * @param Request $request
     * @param ContainerManager $manager
     * @param TranslatorInterface $translator
     * @param string $tabName
     * @return JsonResponse|Response
     * @Route("/markbook/settings/{tabName}", name="markbook_settings")
     * @todo Move to Markbook Module
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(PageManager $pageManager, ContainerManager $manager, TranslatorInterface $translator, string $tabName = 'Features')
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        $settingProvider = ProviderFactory::create(Setting::class);
        $settingProvider->getSettingsByScope('Markbook');
        $container = new Container();

        $form = $this->createForm(MarkbookSettingType::class, null, ['action' => $this->generateUrl('school_admin__markbook_settings')]);

        if ($request->getContent() !== '') {
            $data = [];
            $data['status'] = 'success';
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                $form = $this->createForm(MarkbookSettingType::class, null, ['action' => $this->generateUrl('school_admin__markbook_settings')]);
            } catch (\Exception $e) {
                $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $panel = new Panel('Features');
        $container->setTranslationDomain('SchoolAdmin')->addForm('Features', $form->createView())->addPanel($panel)->setSelectedPanel($tabName)->setTarget('formContent');
        $panel = new Panel('Interface');
        $container->addPanel($panel);
        $panel = new Panel('Warnings');
        $container->addPanel($panel);

        // Finally Finished
        $manager->addContainer($container)->buildContainers();

        return $pageManager->createBreadcrumbs('Markbook Settings',
        )
            ->render(['containers' => $manager->getBuiltContainers()]);

        return $this->render('@KookaburraSchoolAdmin/markbook/settings.html.twig');
    }
}