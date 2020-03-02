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
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
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
     * @param PageManager $pageManager
     * @return JsonResponse
     */
    public function manage(YearGroupPagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $content = ProviderFactory::getRepository(YearGroup::class)->findBy([],['sequenceNumber' => 'ASC']);
        $pagination->setContent($content)->setPageMax(25)
            ->setPaginationScript();
        return $pageManager->createBreadcrumbs('Manage Year Groups')
            ->render(['pagination' => $pagination->toArray()]);
    }

    /**
     * edit
     * @Route("/year/group/{year}/edit/", name="year_group_edit")
     * @Route("/year/group/add/", name="year_group_add")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param YearGroup|null $year
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ?YearGroup $year = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();
        if (!$year instanceof YearGroup) {
            $year = new YearGroup();
            $action = $this->generateUrl('school_admin__year_group_add');
        } else {
            $action = $this->generateUrl('school_admin__year_group_edit', ['year' => $year->getId()]);
        }

        $form = $this->createForm(YearGroupType::class, $year, ['action' => $action]);

        if ($request->getContent() !== '') {
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
        return $pageManager->createBreadcrumbs($year->getId() > 0 ? 'Edit Year Group' : 'Add Year Group',
            [
                ['uri' => 'school_admin__year_group_manage', 'name' => 'Manage Year Groups']
            ]
        )
            ->render(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * delete
     * @Route("/year/group/{year}/delete/", name="year_group_delete")
     * @IsGranted("ROLE_ROUTE")
     * @param YearGroup $year
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @param YearGroupPagination $pagination
     * @param PageManager $pageManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function delete(YearGroup $year, TranslatorInterface $translator, YearGroupPagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $provider = ProviderFactory::create(YearGroup::class);

        $provider->delete($year);

        $content = ProviderFactory::getRepository(YearGroup::class)->findBy([],['sequenceNumber' => 'ASC']);
        $pagination->setContent($content)->setPageMax(25)
            ->setPaginationScript();

        return $pageManager->createBreadcrumbs('Manage Year Groups')
            ->render(['pagination' => $pagination->toArray(), 'messages' => $provider->getMessageManager()->serialiseTranslatedMessages($translator)]);
    }

    /**
     * topOfList
     * @Route("/year/group/{year}/top/of/list/", name="year_group_top_of_list")
     * @IsGranted("ROLE_ROUTE")
     * @param YearGroup $year
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @param YearGroupPagination $pagination
     * @param PageManager $pageManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function topOfList(YearGroup $year, TranslatorInterface $translator, YearGroupPagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $provider = ProviderFactory::create(YearGroup::class);

        $provider->moveToTopOfList($year);

        $content = ProviderFactory::getRepository(YearGroup::class)->findBy([],['sequenceNumber' => 'ASC']);
        $pagination->setContent($content)->setPageMax(25)
            ->setPaginationScript();

        return $pageManager->createBreadcrumbs('Manage Year Groups')
            ->render(['pagination' => $pagination->toArray(), 'messages' => $provider->getMessageManager()->serialiseTranslatedMessages($translator), 'url' => $this->generateUrl('school_admin__year_group_manage')]);
    }


}