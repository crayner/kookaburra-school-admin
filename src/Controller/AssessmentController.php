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

use App\Container\ContainerManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessment;
use Kookaburra\SchoolAdmin\Pagination\ExternalAssessmentPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/external/assessment/{assessment}/edit/", name="external_assessment_edit")
     * @Route("/external/assessment/add/", name="external_assessment_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, Request $request, ?ExternalAssessment $assessment = null)
    {
        if (!$assessment instanceof ExternalAssessment) {
            $assessment = new ExternalAssessment();
            $action = $this->generateUrl('school_admin__external_assessment_add');
        } else {
            $action = $this->generateUrl('school_admin__external_assessment_edit', ['assessment' => $assessment->getId()]);
        }

        $form = $this->createForm(ExternalAssessmentType::class, $assessment, ['action' => $action]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            dump($content);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $assessment->getId();
                $provider = ProviderFactory::create(Assessment::class);
                $data = $provider->persistFlush($assessment, $data);
                if ($data['status'] === 'success')
                    $form = $this->createForm(ExternalAssessmentType::class, $assessment, ['action' => $this->generateUrl('school_admin__external_assessment_edit', ['assessment' => $assessment->getId()])]);
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->singlePanel($form->createView());

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
        $provider = ProviderFactory::create(Assessment::class);

        $provider->delete($assessment);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__external_assessment_manage');
    }
}
