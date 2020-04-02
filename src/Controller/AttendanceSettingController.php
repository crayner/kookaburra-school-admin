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
 * Date: 17/01/2020
 * Time: 08:01
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use Kookaburra\SystemAdmin\Entity\Setting;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Entity\AttendanceCode;
use Kookaburra\SchoolAdmin\Form\AttendanceCLIType;
use Kookaburra\SchoolAdmin\Form\AttendanceCodeType;
use Kookaburra\SchoolAdmin\Form\AttendanceContextType;
use Kookaburra\SchoolAdmin\Form\AttendanceReasonType;
use Kookaburra\SchoolAdmin\Form\AttendanceRegistrationType;
use Kookaburra\SchoolAdmin\Pagination\AttendanceCodePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AttendanceSettingController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class AttendanceSettingController extends AbstractController
{
    /**
     * manage
     * @Route("/attendance/settings/manage/{tabName}", name="attendance_settings")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manage(ContainerManager $manager, PageManager $pageManager, string $tabName = 'Code')
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        ProviderFactory::create(Setting::class)->getSettingsByScope('Attendance');

        $container = new Container();
        $panel = new Panel('Code', 'SchoolAdmin');
        $panel->setPostContent(['attendanceCodePaginationContent']);
        $container->setContentLoader([
            [
                'route' => $this->generateUrl('school_admin__attendance_code_content'),
                'target' => 'attendanceCodePaginationContent',
                'type' => 'pagination',
            ],
        ]);
        $panel->setContent($this->renderView('@KookaburraSchoolAdmin/attendance/code_manage.html.twig'));

        $container->addPanel($panel);

        $form = $this->createForm(AttendanceReasonType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Reasons'])]);
        $panel = new Panel('Reasons', 'SchoolAdmin');
        $container->addForm('Reasons', $form->createView())->addPanel($panel);

        $form = $this->createForm(AttendanceContextType::class, null,['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Context'])]);
        $panel = new Panel('Context', 'SchoolAdmin');
        $container->addForm('Context', $form->createView())->addPanel($panel);

        $form = $this->createForm(AttendanceRegistrationType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Registration'])]);
        $panel = new Panel('Registration', 'SchoolAdmin');
        $container->addForm('Registration', $form->createView())->addPanel($panel);


        $form = $this->createForm(AttendanceCLIType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'CLI'])]);
        $panel = new Panel('CLI', 'SchoolAdmin');
        $container->addForm('CLI', $form->createView())->addPanel($panel);

        $manager->addContainer($container->setSelectedPanel($tabName))->buildContainers();
        return $pageManager->createBreadcrumbs('Alert Levels', [])
            ->render(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * manageContent
     * @param Request $request
     * @param string $tabName
     * @param ContainerManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @Route("/attendance/settings/{tabName}/save/", name="attendance_settings_save", methods={"POST"})
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__attendance_settings'])")
     */
    public function saveSettings(Request $request, string $tabName, ContainerManager $manager, TranslatorInterface $translator)
    {
        $form = $this->createForm(AttendanceReasonType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Reasons'])]);
        switch ($tabName) {
            case 'Reason':
                $form = $this->createForm(AttendanceReasonType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Reasons'])]);
                break;
            case 'Context':
                $form = $this->createForm(AttendanceContextType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Context'])]);
                break;
            case 'Registration':
                $form = $this->createForm(AttendanceRegistrationType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Registration'])]);
                break;
            case 'CLI':
                $form = $this->createForm(AttendanceCLIType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'CLI'])]);
                break;
            default:
                dump($tabName);
        }

        ProviderFactory::create(Setting::class)->handleSettingsForm($form, $request, $translator);
        $data['status'] = ProviderFactory::create(Setting::class)->getStatus();
        $data['errors'] = ProviderFactory::create(Setting::class)->getErrors();
        if (ProviderFactory::create(Setting::class)->getStatus() === 'success') {
            switch ($tabName) {
                case 'Reason':
                    $form = $this->createForm(AttendanceReasonType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Reasons'])]);
                    break;
                case 'Context':
                    $form = $this->createForm(AttendanceContextType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Context'])]);
                    break;
                case 'Registration':
                    $form = $this->createForm(AttendanceRegistrationType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'Registration'])]);
                    break;
                case 'CLI':
                    $form = $this->createForm(AttendanceCLIType::class, null, ['action' => $this->generateUrl('school_admin__attendance_settings_save', ['tabName' => 'CLI'])]);
                    break;
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer();
        }

        return new JsonResponse($data, 200);
    }

    /**
     * manageContent
     * @param AttendanceCodePagination $pagination
     * @return JsonResponse
     * @Route("/attendance/code/content/", name="attendance_code_content")
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__attendance_settings'])")
     */
    public function manageContent(AttendanceCodePagination $pagination)
    {
        try {
            $content = ProviderFactory::getRepository(AttendanceCode::class)->findBy([], ['sequenceNumber' => 'ASC']);
            $pagination->setContent($content)->setAddElementRoute($this->generateUrl('school_admin__attendance_code_add'));
            return new JsonResponse(['content' => $pagination->toArray(), 'pageMax' => $pagination->getPageMax(), 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param AttendanceCode|null $code
     * @return \Symfony\Component\HttpFoundation\Response|JsonResponse
     * @Route("/attendance/code/{code}/edit/", name="attendance_code_edit")
     * @Route("/attendance/code/add/", name="attendance_code_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ?AttendanceCode $code = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        if (!$code instanceof AttendanceCode) {
           $code = new AttendanceCode();
            $action = $this->generateUrl('school_admin__attendance_code_add');
        } else {
            $action = $this->generateUrl('school_admin__attendance_code_edit', ['code' =>$code->getId()]);
        }

        $form = $this->createForm(AttendanceCodeType::class,$code, ['action' => $action]);

        if ($request->getContent() !== '') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $code->getId();
                $provider = ProviderFactory::create(AttendanceCode::class);
                $data = $provider->persistFlush($code, $data);
                if ($data['status'] === 'success' && $id === $code->getId())
                        $form = $this->createForm(AttendanceCodeType::class, $code, ['action' => $this->generateUrl('school_admin__attendance_code_edit', ['code' => $code->getId()])]);
            } else {
                $data = ErrorMessageHelper::getInvalidInputsMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->setReturnRoute($this->generateUrl('school_admin__attendance_settings'))->setAddElementRoute($this->generateUrl('school_admin__attendance_code_add'))->singlePanel($form->createView());

        return $pageManager->createBreadcrumbs($code->getId() > 0 ? 'Edit Attendance Code' : 'Add Attendance Code', [['uri' => 'school_admin__attendance_settings', 'name' => 'Attendance Settings']])
            ->render(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * delete
     * @param AttendanceCode $code
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/attendance/code/{code}/delete/", name="attendance_code_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(AttendanceCode $code, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(AttendanceCode::class);

        $provider->delete($code);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__attendance_settings');
    }
}