<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 21/12/2019
 * Time: 10:27
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Provider\ProviderFactory;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Pagination\AcademicYearPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AcademicYearController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class AcademicYearController extends AbstractController
{
    /**
     * manage
     * @Route("/academic/year/manage/", name="academic_year_manage")
     * @IsGranted("ROLE_ROUTE")
     */
    public function manage(AcademicYearPagination $pagination)
    {
        $content = ProviderFactory::getRepository(AcademicYear::class)->findBy([], ['firstDay' => 'ASC']);
        $pagination->setContent($content)->setPageMax(25)
            ->setPaginationScript();
        return $this->render('@KookaburraSchoolAdmin/academic-year/manage.html.twig');
    }

    /**
     * manage
     * @Route("/academic/year/{year}/edit/", name="academic_year_edit")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(?AcademicYear $year = null)
    {
        return $this->render('@KookaburraSchoolAdmin/academic-year/manage.html.twig');
    }
    /**
     * manage
     * @Route("/academic/year/{year}/delete/", name="academic_year_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(AcademicYear $year)
    {
        return $this->render('@KookaburraSchoolAdmin/academic-year/manage.html.twig');
    }
}