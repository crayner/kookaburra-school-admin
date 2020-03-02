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
 * Date: 31/12/2019
 * Time: 18:06
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\House;
use Kookaburra\SchoolAdmin\Form\HouseType;
use Kookaburra\SchoolAdmin\Pagination\HousePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class HouseController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class HouseController extends AbstractController
{
    /**
     * manage
     * @Route("/house/manage/", name="house_manage")
     * @IsGranted("ROLE_ROUTE")
     * @param HousePagination $pagination
     * @param PageManager $pageManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function manage(HousePagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $content = ProviderFactory::getRepository(House::class)->findBy([], ['name' => 'ASC']);
        $pagination->setContent($content)->setPageMax(10)->setAddElementRoute($this->generateUrl('school_admin__house_add'))
            ->setPaginationScript();
        return $pageManager->createBreadcrumbs('Houses')
            ->render(['pagination' => $pagination->toArray()]);
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param House|null $house
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/house/{house}/edit/", name="house_edit")
     * @Route("/house/add/", name="house_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ?House $house = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();
        if (!$house instanceof House) {
            $house = new House();
            $action = $this->generateUrl('school_admin__house_add');
        } else {
            $action = $this->generateUrl('school_admin__house_edit', ['house' => $house->getId()]);
        }

        $form = $this->createForm(HouseType::class, $house, ['action' => $action]);

        if ($request->getContent() !== '') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $house->getId();
                $provider = ProviderFactory::create(House::class);
                $data = $provider->persistFlush($house, $data);
                if ($data['status'] === 'success')
                    $form = $this->createForm(HouseType::class, $house, ['action' => $this->generateUrl('school_admin__house_edit', ['house' => $house->getId()])]);
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->setReturnRoute($this->generateUrl('school_admin__house_manage'))->singlePanel($form->createView());

        return $pageManager->createBreadcrumbs($house->getId() > 0 ? 'Edit House' : 'Add House',
            [
                ['uri' => 'school_admin__house_manage', 'name' => 'Houses']
            ]
        )
            ->render(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * delete
     * @param House $house
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/house/{house}/delete/", name="house_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(House $house, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(House::class);

        $provider->delete($house);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__house_manage');
    }
}
