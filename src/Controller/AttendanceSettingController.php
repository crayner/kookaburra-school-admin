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
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\AttendanceCode;
use Kookaburra\SchoolAdmin\Form\AttendanceCLIType;
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
     * @param AttendanceCodePagination $pagination
     * @param ContainerManager $manager
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manage(AttendanceCodePagination $pagination, ContainerManager $manager, string $tabName = 'Code')
    {
        $content = ProviderFactory::getRepository(AttendanceCode::class)->findBy([], ['sequenceNumber' => 'ASC']);
        ProviderFactory::create(Setting::class)->getSettingsByScope('Attendance');

        $pagination->setContent($content)
            ->setContentLoader($this->generateUrl('school_admin__attendance_code_content'))
            ->setPaginationScript();

        $container = new Container();
        $panel = new Panel('Code', 'SchoolAdmin');
        $panel->setContent($this->renderView('@KookaburraSchoolAdmin/attendance/code_manage.html.twig'));

        $container->addPanel($panel);

        $form = $this->createForm(AttendanceReasonType::class, null, ['action' => '']);
        $panel = new Panel('Reasons', 'SchoolAdmin');
        $container->addForm('Reasons', $form->createView())->addPanel($panel);

        $form = $this->createForm(AttendanceContextType::class, null, ['action' => '']);
        $panel = new Panel('Context', 'SchoolAdmin');
        $container->addForm('Context', $form->createView())->addPanel($panel);

        $form = $this->createForm(AttendanceRegistrationType::class, null, ['action' => '']);
        $panel = new Panel('Registration', 'SchoolAdmin');
        $container->addForm('Registration', $form->createView())->addPanel($panel);


        $form = $this->createForm(AttendanceCLIType::class, null, ['action' => '']);
        $panel = new Panel('CLI', 'SchoolAdmin');
        $container->addForm('CLI', $form->createView())->addPanel($panel);





        $manager->addContainer($container->setSelectedPanel($tabName))->buildContainers();
        return $this->render('@KookaburraSchoolAdmin/attendance/manage.html.twig');
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
            $pagination->setContent($content);
            return new JsonResponse(['content' => $pagination->getContent(), 'pageMax' => $pagination->getPageMax(), 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param Request $request
     * @param AttendanceCode|null $code
     * @return JsonResponse
     * @Route("/attendance/code/{code}/edit/", name="attendance_code_edit")
     * @Route("/attendance/code/add/", name="attendance_code_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, Request $request, ?AttendanceCode $code = null)
    {
        if (!$code instanceof AttendanceSetting) {
            $attendanceSetting = new AttendanceSetting();
            $action = $this->generateUrl('school_admin__attendance_settings_add');
        } else {
            $action = $this->generateUrl('school_admin__attendance_settings_edit', ['attendanceSetting' => $attendanceSetting->getId()]);
        }

        $form = $this->createForm(AttendanceSettingType::class, $attendanceSetting, ['action' => $action]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $attendanceSetting->getId();
                $provider = ProviderFactory::create(AttendanceSetting::class);
                $data = $provider->persistFlush($attendanceSetting, $data);
                if ($data['status'] === 'success')
                    $form = $this->createForm(AttendanceSettingType::class, $attendanceSetting, ['action' => $this->generateUrl('school_admin__attendance_settings_edit', ['attendanceSetting' => $attendanceSetting->getId()])]);
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/attendance/settings/edit.html.twig',
            [
                'attendanceSetting' => $attendanceSetting,
            ]
        );
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

        return $this->redirectToRoute('school_admin__attendance_settings_manage');
    }

}