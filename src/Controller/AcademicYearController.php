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

use App\Container\ContainerManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Form\AcademicYearType;
use Kookaburra\SchoolAdmin\Pagination\AcademicYearPagination;
use Kookaburra\SystemAdmin\Entity\Role;
use Kookaburra\UserAdmin\Form\RoleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @param AcademicYearPagination $pagination
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manage(AcademicYearPagination $pagination)
    {
        $content = ProviderFactory::getRepository(AcademicYear::class)->findBy([], ['firstDay' => 'ASC']);
        $pagination->setContent($content)->setPageMax(25)
            ->setPaginationScript();
        return $this->render('@KookaburraSchoolAdmin/academic-year/manage.html.twig');
    }

    /**
     * edit
     * @Route("/academic/year/{year}/edit/", name="academic_year_edit")
     * @Route("/academic/year/add/", name="academic_year_add")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param Request $request
     * @param AcademicYear|null $year
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(ContainerManager $manager, Request $request, ?AcademicYear $year = null)
    {
        if (intval($year) === 0) {
            $year = new AcademicYear();
            $action = $this->generateUrl('school_admin__academic_year_add');
        } else {
            $action = $this->generateUrl('school_admin__academic_year_edit', ['year' => $year->getId()]);
        }

        $form = $this->createForm(AcademicYearType::class, $year, ['action' => $action]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $year->getId();
                $provider = ProviderFactory::create(AcademicYear::class);
                $data = $provider->persistFlush($year, $data);
                if ($id !== $year->getId() && $data['status'] === 'success')
                    $form = $this->createForm(AcademicYearType::class, $year, ['action' => $this->generateUrl('school_admin__academic_year_edit', ['year' => $year->getId()])]);
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/academic-year/edit.html.twig');
    }

    /**
     * delete
     * @Route("/academic/year/{year}/delete/", name="academic_year_delete")
     * @IsGranted("ROLE_ROUTE")
     * @param AcademicYear $year
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(AcademicYear $year, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(AcademicYear::class);

        $provider->delete($year);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__academic_year_manage');
    }
}