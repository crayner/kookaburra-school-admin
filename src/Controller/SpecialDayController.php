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
 * Date: 23/12/2019
 * Time: 08:07
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Entity\AcademicYearSpecialDay;
use Kookaburra\SchoolAdmin\Form\SpecialDayType;
use Kookaburra\SchoolAdmin\Manager\SpecialDayManager;
use Kookaburra\SchoolAdmin\Pagination\SpecialDayPagination;
use Kookaburra\SchoolAdmin\Util\AcademicYearHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SpecialDayController
 * @package Kookaburra\SchoolAdmin
 */
class SpecialDayController extends AbstractController
{
    /**
     * manage
     * @Route("/special/day/manage/",name="special_day_manage")
     * @IsGranted("ROLE_ROUTE")
     * @param SpecialDayPagination $pagination
     * @param PageManager $pageManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manage(SpecialDayPagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();

        $content = ProviderFactory::getRepository(AcademicYearSpecialDay::class)->findBy([],['date' => 'ASC']);
        $pagination->setStoreFilterURL($this->generateUrl('school_admin__special_day_filter_store'))->setContent($content)->setPageMax(25)
            ->setPaginationScript()->setAddElementRoute($this->generateUrl('school_admin__special_day_add'));

        $pageManager->createBreadcrumbs('Manage Special Days', []);

        return $pageManager->createResponse(['pagination' => $pagination->toArray()]);
    }

    /**
     * edit
     * @Route("/special/day/{day}/edit/", name="special_day_edit")
     * @Route("/special/day/add/", name="special_day_add")
     * @Route("/special/day/{day}/duplicate/", name="special_day_duplicate")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param AcademicYearSpecialDay|null $day
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ?AcademicYearSpecialDay $day = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        if ($request->attributes->get('_route') === 'school_admin__special_day_duplicate') {
            $copy = clone $day;
            $day = new AcademicYearSpecialDay();
            $day->setName($copy->getName())
                ->setDescription($copy->getDescription())
                ->setAcademicYear(AcademicYearHelper::getNextAcademicYear($copy->getAcademicYear()))
                ->setDate(SpecialDayManager::getDuplicateDate($copy))
                ->setType($copy->getType())
                ->setSchoolClose($copy->getSchoolClose())
                ->setSchoolEnd($copy->getSchoolEnd())
                ->setSchoolStart($copy->getSchoolStart())
                ->setSchoolOpen($copy->getSchoolOpen())
            ;
            $action = $this->generateUrl('school_admin__special_day_add');
        } else if (!$day instanceof AcademicYearSpecialDay) {
            $day = new AcademicYearSpecialDay();
            $action = $this->generateUrl('school_admin__special_day_add');
            $whichYear = $request->getSession()->get('special_day_pagination');
            if (isset($whichYear['filter'])) {
                foreach($whichYear['filter'] as $w)
                    if (mb_strpos($w, 'Academic Year: ') === 0) {
                        $whichYear = $w;
                        break;
                    }
            } else {
                $whichYear = '';
            }
            $year = ProviderFactory::getRepository(AcademicYear::class)->findOneByName(str_replace('Academic Year: ','', $whichYear)) ?: AcademicYearHelper::getCurrentAcademicYear();
            $day->setAcademicYear($year);
        } else {
            $action = $this->generateUrl('school_admin__special_day_edit', ['day' => $day->getId()]);
        }

        $form = $this->createForm(SpecialDayType::class, $day, ['action' => $action]);

        if ($request->getContent() !== '') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $day->getId();
                $provider = ProviderFactory::create(AcademicYearSpecialDay::class);
                $data = $provider->persistFlush($day, $data);
                if ($id !== $day->getId() && $data['status'] === 'success') {
                    $data['status'] = 'redirect';
                    $data['redirect'] = $this->generateUrl('school_admin__special_day_edit', ['day' => $day->getId()]);
                    ErrorMessageHelper::convertToFlash($data, $request->getSession()->getBag('flashes'));
                    return new JsonResponse($data, 200);
                }
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->setReturnRoute($this->generateUrl('school_admin__special_day_manage'))->setAddElementRoute($this->generateUrl('school_admin__special_day_add'))->singlePanel($form->createView());

        $pageManager->createBreadcrumbs(($day->getId() > 0 ? 'Edit Special Day' : 'Add Special Day'), [['uri' => 'school_admin__special_day_manage', 'name' => 'Manage Special Days']]);

        return $pageManager->createResponse(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * delete
     * @Route("/special/day/{day}/delete/", name="special_day_delete")
     * @IsGranted("ROLE_ROUTE")
     * @param AcademicYearSpecialDay $day
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(AcademicYearSpecialDay $day, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(AcademicYearSpecialDay::class);

        $provider->delete($day);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__special_day_manage');
    }

    /**
     * storeFilter
     * @param Request $request
     * @param SpecialDayPagination $pagination
     * @return JsonResponse
     * @Route("/special/day/filter/store/",name="special_day_filter_store", methods={"POST"})
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__special_day_manage'])")
     */
    public function storeFilter(Request $request, SpecialDayPagination $pagination)
    {
        $content = json_decode($request->getContent(), true);
        $pagination->writeInitialFilter($content);
        return new JsonResponse([],200);
    }
}
