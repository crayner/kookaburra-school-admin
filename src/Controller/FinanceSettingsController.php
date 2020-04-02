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
 * Time: 11:25
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use Kookaburra\SystemAdmin\Entity\Setting;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Form\FinanceSettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FinanceSettingsController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class FinanceSettingsController extends AbstractController
{
    /**
     * settings
     * @param PageManager $pageManager
     * @param ContainerManager $manager
     * @param TranslatorInterface $translator
     * @param string $tabName
     * @return JsonResponse|Response
     * @Route("/finance/settings/{tabName}", name="finance_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(PageManager $pageManager, ContainerManager $manager, TranslatorInterface $translator, string $tabName = 'General')
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();
        $settingProvider = ProviderFactory::create(Setting::class);
        $settingProvider->getSettingsByScope('Finance');
        $container = new Container();

        $form = $this->createForm(FinanceSettingsType::class, null, ['action' => $this->generateUrl('school_admin__finance_settings', ['tabName' => $tabName])]);

        if ($request->getContent() !== '') {
            $data = [];
            $data['status'] = 'success';
            try {
                $data['errors'] = $settingProvider->handleSettingsForm($form, $request, $translator);
                if ($data['status'] === 'success')
                    $form = $this->createForm(FinanceSettingsType::class, null, ['action' => $this->generateUrl('school_admin__finance_settings', ['tabName' => $tabName])]);
            } catch (\Exception $e) {
                $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $panel = new Panel('General');
        $container->setTranslationDomain('SchoolAdmin')->addForm('General', $form->createView())->addPanel($panel)->setSelectedPanel($tabName);
        $panel = new Panel('Invoices');
        $container->addPanel($panel);
        $panel = new Panel('Receipts');
        $container->addPanel($panel);
        $panel = new Panel('Reminders');
        $container->addPanel($panel);
        $panel = new Panel('Expenses');
        $container->addPanel($panel);

        // Finally Finished
        $manager->addContainer($container)->buildContainers();

        return $pageManager->createBreadcrumbs('Finance Settings')
            ->render(['containers' => $manager->getBuiltContainers()]);
        return $this->render('@KookaburraSchoolAdmin/finance/settings.html.twig');
    }
}