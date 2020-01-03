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
 * Date: 3/01/2020
 * Time: 11:55
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Kookaburra\SchoolAdmin\Form\YearGroupType;
use Kookaburra\SchoolAdmin\Pagination\YearGroupPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class YearGroupController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class YearGroupController extends AbstractController
{
    /**
     * manage
     * @Route("/year/group/manage/",name="year_group_manage")
     * @IsGranted("ROLE_ROUTE")
     * @param YearGroupPagination $pagination
     * @return
     */
    public function manage(YearGroupPagination $pagination)
    {
        $content = ProviderFactory::getRepository(YearGroup::class)->findBy([],['sequenceNumber' => 'ASC']);
        $pagination->setContent($content)->setPageMax(25)
            ->setPaginationScript();
        return $this->render('@KookaburraSchoolAdmin/year-group/manage.html.twig');
    }

    /**
     * edit
     * @Route("/year/group/{year}/edit/", name="year_group_edit")
     * @Route("/year/group/add/", name="year_group_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, Request $request, ?YearGroup $year = null)
    {
        if (!$year instanceof YearGroup) {
            $year = new YearGroup();
            $action = $this->generateUrl('school_admin__year_group_add');
        } else {
            $action = $this->generateUrl('school_admin__year_group_edit', ['year' => $year->getId()]);
        }

        $form = $this->createForm(YearGroupType::class, $year, ['action' => $action]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $provider = ProviderFactory::create(YearGroup::class);
                $data = $provider->persistFlush($year, $data);
                if ($data['status'] === 'success') {
                    $form = $this->createForm(YearGroupType::class, $year, ['action' => $this->generateUrl('school_admin__year_group_edit', ['year' => $year->getId()])]);
                }
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/year-group/edit.html.twig',
            [
                'year' => $year,
            ]
        );
    }

    /**
     * delete
     * @Route("/year/group/{year}/delete/", name="year_group_delete")
     * @IsGranted("ROLE_ROUTE")
     * @param YearGroup $year
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(YearGroup $year, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(YearGroup::class);

        $provider->delete($year);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__year_group_manage');
    }

    /**
     * topOfList
     * @Route("/year/group/{year}/top/of/list/", name="year_group_top_of_list")
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__year_group_manage'])")
     * @param YearGroup $year
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function topOfList(YearGroup $year, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(YearGroup::class);

        $provider->moveToTopOfList($year);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__year_group_manage');
    }
}