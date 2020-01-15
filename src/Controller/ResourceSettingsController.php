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
 * Time: 15:05
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Form\ResourceSettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ResourceSettingsController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class ResourceSettingsController extends AbstractController
{
    /**
     * settings
     * @param Request $request
     * @param ContainerManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse|Response
     * @Route("/resource/settings/{tabName}", name="resource_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(Request $request, ContainerManager $manager, TranslatorInterface $translator, ?string $tabName = 'Category')
    {
        $settingProvider = ProviderFactory::create(Setting::class);
        $settingProvider->getSettingsByScope('Resources');

        $form = $this->createForm(ResourceSettingsType::class, null, ['action' => $this->generateUrl('school_admin__resource_settings', ['tabName' => $tabName])]);

        if ($request->getContentType() === 'json') {
            $data = [];
            $data['status'] = 'success';
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                if ($data['status'] === 'success')
                    $form = $this->createForm(ResourceSettingsType::class, null, ['action' => $this->generateUrl('school_admin__resource_settings', ['tabName' => $tabName])]);
            } catch (\Exception $e) {
                $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }


        // Finally Finished
        $container = new Container();
        $panel = new Panel('Category', 'SchoolAdmin');
        $container->setTranslationDomain('SchoolAdmin')->addForm('Features', $form->createView())->addPanel($panel)->setSelectedPanel($tabName)->setTarget('formContent');
        $panel = new Panel('Purpose');
        $container->addPanel($panel);

        // Finally Finished
        $manager->addContainer($container)->buildContainers();

        return $this->render('@KookaburraSchoolAdmin/resource/settings.html.twig');
    }
}
