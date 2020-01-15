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
 * Time: 10:07
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Form\MessengerSettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class MessengerSettingsController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class MessengerSettingsController extends AbstractController
{
    /**
     * settings
     * @param Request $request
     * @param ContainerManager $manager
     * @param TranslatorInterface $translator
     * @param string $tabName
     * @return JsonResponse|Response
     * @Route("/messenger/settings/", name="messenger_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(Request $request, ContainerManager $manager, TranslatorInterface $translator, string $tabName = 'Features')
    {
        $settingProvider = ProviderFactory::create(Setting::class);
        $settingProvider->getSettingsByScope('Messenger');

        $form = $this->createForm(MessengerSettingsType::class, null, ['action' => $this->generateUrl('school_admin__messenger_settings')]);

        if ($request->getContentType() === 'json') {
            $data = [];
            $data['status'] = 'success';
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                if ($data['status'] === 'success')
                    $form = $this->createForm(MessengerSettingsType::class, null, ['action' => $this->generateUrl('school_admin__messenger_settings')]);
            } catch (\Exception $e) {
                $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }


        // Finally Finished
        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/messenger/settings.html.twig');
    }
}
