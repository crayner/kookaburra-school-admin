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
 * Time: 14:00
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Entity\Setting;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Form\ActivitySettingsType;
use Kookaburra\SchoolAdmin\Form\MessengerSettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ActivitySettingsController
 * @package Kookaburra\SchoolAdmin\Controller
 * @todo Move this code to Activity Module when created.
 */
class ActivitySettingsController extends AbstractController
{
    /**
     * settings
     * @param PageManager $pageManager
     * @param ContainerManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse|Response
     * @Route("/activity/settings/", name="activity_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(PageManager $pageManager, ContainerManager $manager, TranslatorInterface $translator)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        $settingProvider = ProviderFactory::create(Setting::class);
        $settingProvider->getSettingsByScope('Activities');

        $form = $this->createForm(ActivitySettingsType::class, null, ['action' => $this->generateUrl('school_admin__activity_settings')]);

        if ($request->getContent() !== '') {
            $data = [];
            $data['status'] = 'success';
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                if ($data['status'] === 'success')
                    $form = $this->createForm(ActivitySettingsType::class, null, ['action' => $this->generateUrl('school_admin__activity_settings')]);
            } catch (\Exception $e) {
                $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        // Finally Finished
        $manager->singlePanel($form->createView());
        return $pageManager->createBreadcrumbs('Activity Settings', [])
            ->render(['containers' => $manager->getBuiltContainers()]);
    }
}