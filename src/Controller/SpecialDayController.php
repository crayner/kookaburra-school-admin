<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/12/2019
 * Time: 08:07
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\AcademicYearSpecialDay;
use Kookaburra\SchoolAdmin\Form\SpecialDayType;
use Kookaburra\SchoolAdmin\Pagination\SpecialDayPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     */
    public function manage(SpecialDayPagination $pagination)
    {
        $content = ProviderFactory::getRepository(AcademicYearSpecialDay::class)->findBy([],['date' => 'ASC']);
        $pagination->setContent($content)->setPageMax(25)
            ->setPaginationScript();
        return $this->render('@KookaburraSchoolAdmin/special-day/manage.html.twig');
    }

    /**
     * edit
     * @Route("/special/day/{day}/edit/", name="special_day_edit")
     * @Route("/special/day/add/", name="special_day_add")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param Request $request
     * @param AcademicYearSpecialDay|null $day
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(ContainerManager $manager, Request $request, ?AcademicYearSpecialDay $day = null)
    {
        if (!$day instanceof AcademicYearSpecialDay) {
            $day = new AcademicYearSpecialDay();
            $action = $this->generateUrl('school_admin__special_day_add');
        } else {
            $action = $this->generateUrl('school_admin__special_day_edit', ['day' => $day->getId()]);
        }

        $form = $this->createForm(SpecialDayType::class, $day, ['action' => $action]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $day->getId();
                $provider = ProviderFactory::create(AcademicYearSpecialDay::class);
                $data = $provider->persistFlush($day, $data);
                if ($id !== $day->getId() && $data['status'] === 'success')
                    $form = $this->createForm(SpecialDayType::class, $day, ['action' => $this->generateUrl('school_admin__special_day_edit', ['day' => $day->getId()])]);
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/special-day/edit.html.twig',
            [
                'day' => $day,
            ]
        );
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
}
