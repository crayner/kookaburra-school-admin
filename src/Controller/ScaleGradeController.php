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
use Kookaburra\SchoolAdmin\Entity\Scale;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;
use Kookaburra\SchoolAdmin\Manager\Hidden\ScaleGradeSort;
use Kookaburra\SchoolAdmin\Pagination\ScaleGradePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
            $pagination->setContent($content);
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
     */
    public function edit(Request $request, ContainerManager $manager, Scale $scale, ?ScaleGrade $grade = null)
    {

    }

    /**
     * delete
     * @param Scale $scale
     * @param ScaleGrade $grade
     * @Route("/scale/{scale}/grade/{grade}/delete/",name="scale_grade_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(Scale $scale, ScaleGrade $grade)
    {

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