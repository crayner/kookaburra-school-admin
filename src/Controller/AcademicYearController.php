<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 21/12/2019
 * Time: 10:27
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Manager\PageManager;
use App\Manager\ScriptManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Form\AcademicYearType;
use Kookaburra\SchoolAdmin\Manager\Hidden\CalendarDisplayManager;
use Kookaburra\SchoolAdmin\Pagination\AcademicYearPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Route("/", name="default")
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__academic_year_manage'])")
     * @param AcademicYearPagination $pagination
     * @param PageManager $pageManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manage(AcademicYearPagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();

        $content = ProviderFactory::getRepository(AcademicYear::class)->findBy([], ['firstDay' => 'ASC']);
        $pagination->setContent($content)->setPageMax(25)
            ->setPaginationScript()->setAddElementRoute($this->generateUrl('school_admin__academic_year_add'));

        $pageManager->createBreadcrumbs('Academic Year Manage', []);
        $pageManager->getBreadCrumbs();

        return $pageManager->createResponse(['pagination' => $pagination->toArray()]);
    }

    /**
     * edit
     * @Route("/academic/year/{year}/edit/", name="academic_year_edit")
     * @Route("/academic/year/add/", name="academic_year_add")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param AcademicYear|null $year
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ?AcademicYear $year = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        if (!$year instanceof AcademicYear) {
            $year = new AcademicYear();
            $action = $this->generateUrl('school_admin__academic_year_add');
        } else {
            $action = $this->generateUrl('school_admin__academic_year_edit', ['year' => $year->getId()]);
        }

        $form = $this->createForm(AcademicYearType::class, $year, ['action' => $action]);

        if ($request->getContent() !== '') {
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

        $pageManager->createBreadcrumbs($year->getId() > 0 ? 'Edit Academic Year' : 'Add Academic Year',
            [
                ['uri' => 'school_admin__academic_year_manage', 'name' => 'Manage Academic Years']
            ]
        );

        return $pageManager->createResponse(['containers' => $manager->getBuiltContainers()]);
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

    /**
     * display
     * @param AcademicYear $year
     * @param Request $request
     * @param ScriptManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/academic/year/{year}/display/", name="academic_year_display")
     * @IsGranted("ROLE_USER")
     * @throws \Exception
     */
    public function display(AcademicYear $year, Request $request, ScriptManager $manager)
    {
        $calendar = new CalendarDisplayManager($request->getLocale());
        $manager->addPageStyle('@KookaburraSchoolAdmin/academic-year/calendar.css.twig');
        $calendar->createYear($year);
        return $this->render('@KookaburraSchoolAdmin/academic-year/display.html.twig', [
            'calendar' => $calendar,
        ]);
    }
}