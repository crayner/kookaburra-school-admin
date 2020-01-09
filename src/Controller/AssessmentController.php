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
 * Date: 6/01/2020
 * Time: 13:07
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessment;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Form\ExternalAssessmentFieldType;
use Kookaburra\SchoolAdmin\Form\ExternalAssessmentType;
use Kookaburra\SchoolAdmin\Form\FormalAssessmentType;
use Kookaburra\SchoolAdmin\Manager\Hidden\FormalAssessmentManager;
use Kookaburra\SchoolAdmin\Pagination\ExternalAssessmentFieldPagination;
use Kookaburra\SchoolAdmin\Pagination\ExternalAssessmentPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AssessmentController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class AssessmentController extends AbstractController
{
    /**
     * manage
     * @Route("/external/assessments/manage/", name="external_assessments_manage")
     * @IsGranted("ROLE_ROUTE")
     * @param ExternalAssessmentPagination $pagination
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manage(ExternalAssessmentPagination $pagination)
    {
        $content = ProviderFactory::getRepository(ExternalAssessment::class)->findBy([], ['name' => 'ASC']);
        $pagination->setContent($content)
            ->setPaginationScript();
        return $this->render('@KookaburraSchoolAdmin/assessment/manage.html.twig');
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param Request $request
     * @param ExternalAssessment|null $assessment
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/external/assessment/{assessment}/edit/{tabName}", name="external_assessment_edit")
     * @Route("/external/assessment/add/{tabName}", name="external_assessment_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, Request $request, ?ExternalAssessment $assessment = null, string $tabName = 'Details')
    {
        if (!$assessment instanceof ExternalAssessment) {
            $assessment = new ExternalAssessment();
            $action = $this->generateUrl('school_admin__external_assessment_add');
        } else {
            $action = $this->generateUrl('school_admin__external_assessment_edit', ['assessment' => $assessment->getId()]);
        }

        $form = $this->createForm(ExternalAssessmentType::class, $assessment, ['action' => $action]);

        $container = new Container();
        $container->setTarget('formContent')->setSelectedPanel($tabName);

        $panel = new Panel('Details', 'SchoolAdmin');
        $container->addForm('Details', $form->createView())->addPanel($panel);


        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $assessment->getId();
                $provider = ProviderFactory::create(ExternalAssessment::class);
                $data = $provider->persistFlush($assessment, $data);
                if ($data['status'] === 'success') {
                    $form = $this->createForm(ExternalAssessmentType::class, $assessment, ['action' => $this->generateUrl('school_admin__external_assessment_edit', ['assessment' => $assessment->getId()])]);
                    if ($id !== $assessment->getId()) {
                        $data['redirect'] = $this->generateUrl('school_admin__external_assessment_edit', ['assessment' => $assessment->getId()]);
                        $data['status'] = 'redirect';
                        ErrorMessageHelper::convertToFlash($data, $request->getSession()->getBag('flashes'));
                    }
                }
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        if ($assessment->getId() > 0) {
            $panel = new Panel('Fields', 'SchoolAdmin');
            $panel->setPreContent(['fieldHeaderContent','fieldPaginationContent']);
            $container->addPanel($panel)->setContentLoader([
                [
                    'route' => $this->generateUrl('school_admin__external_assessment_field_header', ['assessment' => $assessment->getId()]),
                    'target' => 'fieldHeaderContent',
                    'type' => 'html',
                ],
                [
                    'route' => $this->generateUrl('school_admin__external_assessment_field_loader', ['assessment' => $assessment->getId()]),
                    'target' => 'fieldPaginationContent',
                    'type' => 'pagination',
                ],
            ]);
        }

        $manager->addContainer($container)->buildContainers();

        return $this->render('@KookaburraSchoolAdmin/assessment/edit.html.twig',
            [
                'assessment' => $assessment,
            ]
        );
    }

    /**
     * delete
     * @param ExternalAssessment $assessment
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/external/assessment/{assessment}/delete/", name="external_assessment_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(ExternalAssessment $assessment, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(ExternalAssessment::class);

        $provider->delete($assessment);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__external_assessments_manage');
    }

    /**
     * manageContent
     * @Route("/external/assessment/{assessment}/loader/", name="external_assessment_field_loader")
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__external_assessments_manage'])")
     * @return JsonResponse
     */
    public function manageContent(ExternalAssessmentFieldPagination $pagination, ?ExternalAssessment $assessment)
    {
        try {
            $content = ProviderFactory::getRepository(ExternalAssessmentField::class)->findByAssessment($assessment);
            $pagination->setContent($content);
            return new JsonResponse(['content' => $pagination->toArray(), 'pageMax' => $pagination->getPageMax(), 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * manageContent
     * @Route("/external/assessment/{assessment}/header/", name="external_assessment_field_header")
     * @return JsonResponse
     */
    public function fieldContentHeader(ExternalAssessment $assessment)
    {
        $content = [
            'h3' => [
                'attr' => [],
                'children' => [
                    'button' => [
                        'attr' => [
                            'className' => 'close-button gray',
                            'title' => TranslationsHelper::translate('Add Field', [], 'SchoolAdmin'),
                            'type' => "button",
                        ],
                        'children' => [
                            'span' => [
                                'attr' => [
                                    'className' => 'fas fa-plus-circle fw',
                                ],
                                'content' => '',
                            ],
                        ],
                        'onClick' => ['function' => 'openUrl', 'url' => $this->generateUrl('school_admin__external_assessment_field_add', ['assessment' => $assessment->getId()]), 'target' => '_self'],
                        'content' => '',
                    ],
                ],
                'content' => TranslationsHelper::translate('Manage Assessment Fields for {name}', ['{name}' =>  $assessment->getName()], 'SchoolAdmin') ,
            ],
        ];

        return new JsonResponse(['content' => json_encode($content), 'status' => 'success'], 200);
    }

    /**
     * editField
     * @Route("/external/assessment/{assessment}/field/{field}/edit/", name="external_assessment_field_edit")
     * @Route("/external/assessment/{assessment}/field/add/", name="external_assessment_field_add")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param Request $request
     * @param ExternalAssessmentField|null $field
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editField(ContainerManager $manager, Request $request, ExternalAssessment $assessment, ?ExternalAssessmentField $field = null)
    {
        if (!$field instanceof ExternalAssessmentField) {
            $field = new ExternalAssessmentField();
            $field->setExternalAssessment($assessment);
            $action = $this->generateUrl('school_admin__external_assessment_field_add', ['assessment' => $assessment->getId()]);
        } else {
            $action = $this->generateUrl('school_admin__external_assessment_field_edit', ['assessment' => $assessment->getId(), 'field' => $field->getId()]);
        }

        $form = $this->createForm(ExternalAssessmentFieldType::class, $field, ['action' => $action]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $field->getId();
                $provider = ProviderFactory::create(ExternalAssessmentField::class);
                $data = $provider->persistFlush($field, $data);
                if ($data['status'] === 'success')
                    $form = $this->createForm(ExternalAssessmentFieldType::class, $field, ['action' => $this->generateUrl('school_admin__external_assessment_field_edit', ['assessment' => $assessment->getId(), 'field' => $field->getId()])]);
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/assessment/field/edit.html.twig',
            [
                'field' => $field,
            ]
        );
    }

    /**
     * deleteField
     * @Route("/external/assessment/field/{field}/delete/", name="external_assessment_field_delete")
     * @IsGranted("ROLE_ROUTE")
     * @param ExternalAssessmentField $field
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteField(ExternalAssessmentField $field, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(ExternalAssessmentField::class);

        $provider->delete($field);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__external_assessment_edit', ['assessment' => $field->getExternalAssessment()->getId(), 'tabName' => 'Fields']);
    }

    /**
     * formalAssessment
     * @Route("formal/assessment/{tabName}", name="formal_assessment")
     * @IsGranted("ROLE_ROUTE")
     */
    public function formalAssessment(Request $request, ContainerManager $manager, TranslatorInterface $translator, string $tabName = 'Internal')
    {
        // System Settings
        $form = $this->createForm(FormalAssessmentType::class, null, ['action' => $this->generateUrl('school_admin__formal_assessment',)]);

        if ($request->getContentType() === 'json') {
            $data = [];
            $data['status'] = 'success';
            try {
                $data['errors'] = ProviderFactory::create(Setting::class)->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        if ($request->request->has('formal_external_assessment')) {
            $assessments = $request->request->get('formal_external_assessment');
            if ($this->isCsrfTokenValid('formal_external_assessment', $assessments['_token'])) {
                unset($assessments['_token']);
                ProviderFactory::create(Setting::class)->setSettingByScope('School Admin', 'primaryExternalAssessmentByYearGroup', serialize($assessments));
            }
        }

        $manager->singlePanel($form->createView());

        $fa = new FormalAssessmentManager();

        return $this->render('@KookaburraSchoolAdmin/assessment/settings.html.twig', [
            'assessments' => $fa->createFormContent(),
        ]);
    }
}
