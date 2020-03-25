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

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Manager\PageManager;
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
     * @param PageManager $pageManager
     * @return JsonResponse
     * @Route("/scale/{scale}/grade/manage",name="scale_grade_manage")
     * @IsGranted("ROLE_ROUTE")
     */
    public function manage(ScaleGradePagination $pagination, Scale $scale, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();

        $repository = ProviderFactory::getRepository(ScaleGrade::class);
        $content = $repository->findBy(['scale' => $scale],['sequenceNumber' => 'ASC']);
        $pagination->setContent($content)
            ->setReturnRoute($this->generateUrl('school_admin__scale_edit', ['scale' => $scale->getId(), 'tabName' => 'Grades']))
            ->setAddElementRoute($this->generateUrl('school_admin__scale_grade_add', ['scale' => $scale->getId()]));
        return $pageManager->createBreadcrumbs('Manage Scale Grade', [
            ['uri' => 'school_admin__scale_manage', 'name' => 'Manage Scales'],
            ['uri' => 'school_admin__scale_edit', 'name' => 'Edit Scale ({name})', 'trans_params' => ['{name}' => $scale->getName()], 'uri_params' => ['scale' => $scale->getId(), 'tabName' => 'Grades']]
        ])
            ->render(['pagination' => $pagination->toArray()]);
    }

    /**
     * edit
     * @param PageManager $pageManager
     * @param ContainerManager $manager
     * @param Scale $scale
     * @param ScaleGrade|null $grade
     * @return JsonResponse|Response
     * @Route("/scale/{scale}/grade/{grade}/edit/",name="scale_grade_edit")
     * @Route("/scale/{scale}/grade/add/",name="scale_grade_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(PageManager $pageManager, ContainerManager $manager, Scale $scale, ?ScaleGrade $grade = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        if ($request->get('_route') === 'school_admin__scale_grade_add') {
            $grade = new ScaleGrade();
            $action = $this->generateUrl('school_admin__scale_grade_add', ['scale' => $scale->getId()]);
        } else {
            $action = $this->generateUrl('school_admin__scale_grade_edit', ['scale' => $scale->getId(), 'grade' => $grade->getId()]);
        }
        $grade->setScale($scale);

        $form = $this->createForm(ScaleGradeType::class, $grade, ['action' => $action]);

        if ($request->getContent() !== '') {
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
            $data['form'] = $manager->getFormFromContainer();

            return new JsonResponse($data, 200);
        }

        $manager->setReturnRoute($this->generateUrl('school_admin__scale_edit', ['scale' => $scale->getId(), 'tabName' => 'Grades']))
            ->singlePanel($form->createView());

        return $pageManager->createBreadcrumbs($grade->getId() > 0 ? 'Edit Grade Scale' : 'Add Grade Scale',
            [
                ['uri' => 'school_admin__scale_manage', 'name' => 'Manage Scales'],
                ['uri' => 'school_admin__scale_edit', 'name' => 'Edit Scale ({name})', 'trans_params' => ['{name}' => $scale->getName()], 'uri_params' => ['scale' => $scale->getId(), 'tabName' => 'Grades']]
            ]
        )
            ->render(['containers' => $manager->getBuiltContainers()]);
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