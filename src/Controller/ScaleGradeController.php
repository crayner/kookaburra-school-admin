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
 * Date: 13/01/2020
 * Time: 07:47
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Entity\Scale;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;
use Kookaburra\SchoolAdmin\Form\ScaleGradeType;
use Kookaburra\SchoolAdmin\Manager\Hidden\ScaleGradeSort;
use Kookaburra\SchoolAdmin\Pagination\ScaleGradePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ScaleGradeController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class ScaleGradeController extends AbstractController
{
    /**
     * manage
     * @param ScaleGradePagination $pagination
     * @param Scale $scale
     * @Route("/scale/{scale}/grade/manage",name="scale_grade_manage")
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__scale_grade_edit'])")
     * @return JsonResponse
     */
    public function manage(ScaleGradePagination $pagination, Scale $scale)
    {
        try {
            $repository = ProviderFactory::getRepository(ScaleGrade::class);
            $content = $repository->findBy(['scale' => $scale],['sequenceNumber' => 'ASC']);
            $pagination->setContent($content)
                ->setAddElementRoute($this->generateUrl('school_admin__scale_grade_add', ['scale' => $scale->getId()]));
            return new JsonResponse(['content' => $pagination->toArray(), 'pageMax' => $pagination->getPageMax(), 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * edit
     * @param Request $request
     * @param ContainerManager $manager
     * @param Scale $scale
     * @param ScaleGrade|null $grade
     * @Route("/scale/{scale}/grade/{grade}/edit/",name="scale_grade_edit")
     * @Route("/scale/{scale}/grade/add/",name="scale_grade_add")
     * @IsGranted("ROLE_ROUTE")
     * @return JsonResponse|Response
     */
    public function edit(Request $request, ContainerManager $manager, Scale $scale, ?ScaleGrade $grade = null)
    {
        if ($request->get('_route') === 'school_admin__scale_grade_add') {
            $grade = new ScaleGrade();
            $action = $this->generateUrl('school_admin__scale_grade_add', ['scale' => $scale->getId()]);
        } else {
            $action = $this->generateUrl('school_admin__scale_grade_edit', ['scale' => $scale->getId(), 'grade' => $grade->getId()]);
        }
        $grade->setScale($scale);

        $form = $this->createForm(ScaleGradeType::class, $grade, ['action' => $action]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $grade->getId();
                $provider = ProviderFactory::create(ScaleGrade::class);
                $data = $provider->persistFlush($grade, $data);
                if ($data['status'] === 'success') {
                    $form = $this->createForm(ScaleGradeType::class, $grade, ['action' => $action]);
                    if ($id !== $grade->getId()) {
                        ErrorMessageHelper::convertToFlash($data, $request->getSession()->getBag('flashes'));
                        $data['redirect'] = $this->generateUrl('school_admin__scale_grade_edit', ['scale' => $scale->getId(), 'grade' => $grade->getId()]);
                        $data['status'] = 'redirect';
                    }
                }
            } else {
                $data = ErrorMessageHelper::getInvalidInputsMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $manager->singlePanel($form->createView());
        return $this->render('@KookaburraSchoolAdmin/scale/grade/edit.html.twig',
            [
                'scale' => $scale,
                'grade' => $grade,
            ]
        );

    }

    /**
     * delete
     * @param Scale $scale
     * @param ScaleGrade $grade
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     * @Route("/scale/{scale}/grade/{grade}/delete/",name="scale_grade_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(Scale $scale, ScaleGrade $grade, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(ScaleGrade::class);
        if ($scale === $grade->getScale()) {

            if ($scale->getLowestAcceptable() === $grade)
            {
                $scale->setLowestAcceptable(null);
                $provider->getEntityManager()->persist($scale);
                $provider->getEntityManager()->flush();
            }

            $provider->delete($grade);

            $provider->getMessageManager()->pushToFlash($flashBag, $translator);
        } else {
            $flashBag->add('error', ['return.error.1', [], 'messages']);
        }
        return $this->redirectToRoute('school_admin__scale_edit', ['tabName' => 'Grades', 'scale' => $scale->getId()]);
    }

    /**
     * sortGrades
     * @param ScaleGradePagination $pagination
     * @param ScaleGrade $source
     * @param ScaleGrade $target
     * @return JsonResponse
     * @Route("/scale/grade/{source}/{target}/sort/",name="scale_grade_sort")
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__scale_grade_edit'])")
     */
    public function sortGrades(ScaleGradePagination $pagination, ScaleGrade $source, ScaleGrade $target)
    {
        $manager = new ScaleGradeSort($source, $target, $pagination);

        return new JsonResponse($manager->getDetails(), 200);
    }
}