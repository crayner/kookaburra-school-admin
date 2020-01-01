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
     */
    public function manage(HousePagination $pagination)
    {
        $content = ProviderFactory::getRepository(House::class)->findBy([], ['name' => 'ASC']);
        $pagination->setContent($content)->setPageMax(10)
            ->setPaginationScript();
        return $this->render('@KookaburraSchoolAdmin/house/manage.html.twig');
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param Request $request
     * @param House|null $house
     * @Route("/house/{house}/edit/", name="house_edit")
     * @Route("/house/add/", name="house_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, Request $request, ?House $house = null)
    {
        if (!$house instanceof House) {
            $house = new House();
            $action = $this->generateUrl('school_admin__house_add');
        } else {
            $action = $this->generateUrl('school_admin__house_edit', ['house' => $house->getId()]);
        }

        $form = $this->createForm(HouseType::class, $house, ['action' => $action]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            dump($content);
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
        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/house/edit.html.twig',
            [
                'house' => $house,
            ]
        );
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