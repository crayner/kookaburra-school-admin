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
 * Date: 18/01/2020
 * Time: 08:21
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Entity\Setting;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Entity\INDescriptor;
use Kookaburra\SchoolAdmin\Form\INDescriptorType;
use Kookaburra\SchoolAdmin\Form\INTemplatesType;
use Kookaburra\SchoolAdmin\Pagination\IndividualNeedsPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class IndividualNeedsController
 * @package Kookaburra\SchoolAdmin\Controller
 * @todo Move this code to Individual Needs Module when created.
 */
class IndividualNeedsController extends AbstractController
{
    /**
     * manage
     * @Route("/individual/needs/manage/{tabName}", name="in_manage")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manage(ContainerManager $manager, PageManager $pageManager, string $tabName = 'Descriptors')
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        ProviderFactory::create(Setting::class)->getSettingsByScope('Individual Needs');
        $container = new Container();
        $panel = new Panel('Descriptors', 'SchoolAdmin');
        $panel->setPostContent(['individualNeedsPaginationContent']);
        $container->setContentLoader([
            [
                'route' => $this->generateUrl('school_admin__in_manage_content'),
                'target' => 'individualNeedsPaginationContent',
                'type' => 'pagination',
            ],
        ]);
        $panel->setContent($this->renderView('@KookaburraSchoolAdmin/individual-needs/code_manage.html.twig'));

        $container->addPanel($panel);

        $form = $this->createForm(INTemplatesType::class, null, ['action' => $this->generateUrl('school_admin__in_save')]);
        $panel = new Panel('Templates', 'SchoolAdmin');
        $container->addForm('Templates', $form->createView())->addPanel($panel);

        $manager->addContainer($container->setSelectedPanel($tabName))->buildContainers();
        return $pageManager->createBreadcrumbs( 'Individual Needs',[])
            ->render(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * manageContent
     * @param Request $request
     * @param ContainerManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @Route("/individual/needs/save/", name="in_save", methods={"POST"})
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__in_manage'])")
     */
    public function saveSettings(Request $request, ContainerManager $manager, TranslatorInterface $translator)
    {
        $form = $this->createForm(INTemplatesType::class, null, ['action' => $this->generateUrl('school_admin__in_save')]);

        ProviderFactory::create(Setting::class)->handleSettingsForm($form, $request, $translator);
        $data['status'] = ProviderFactory::create(Setting::class)->getStatus();
        $data['errors'] = ProviderFactory::create(Setting::class)->getErrors();
        if (ProviderFactory::create(Setting::class)->getStatus() === 'success') {
            $form = $this->createForm(INTemplatesType::class, null, ['action' => $this->generateUrl('school_admin__in_save')]);

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer();
        }

        return new JsonResponse($data, 200);
    }

    /**
     * manageContent
     * @param IndividualNeedsPagination $pagination
     * @return JsonResponse
     * @Route("/individual/needs/content/", name="in_manage_content")
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__in_manage'])")
     */
    public function manageContent(IndividualNeedsPagination $pagination)
    {
        try {
            $content = ProviderFactory::getRepository(INDescriptor::class)->findBy([], ['sequenceNumber' => 'ASC']);
            $pagination->setContent($content)->setAddElementRoute($this->generateUrl('school_admin__in_add'));
            return new JsonResponse(['content' => $pagination->toArray(), 'pageMax' => $pagination->getPageMax(), 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param INDescriptor|null $need
     * @return \Symfony\Component\HttpFoundation\Response|JsonResponse
     * @Route("/individual/needs/{need}/edit/", name="in_edit")
     * @Route("/individual/needs/add/", name="in_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ?INDescriptor $need = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();
        if (!$need instanceof INDescriptor) {
            $need = new INDescriptor();
            $action = $this->generateUrl('school_admin__in_add');
        } else {
            $action = $this->generateUrl('school_admin__in_edit', ['need' =>$need->getId()]);
        }

        $form = $this->createForm(INDescriptorType::class, $need, ['action' => $action]);

        if ($request->getContent() !== '') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $need->getId();
                $provider = ProviderFactory::create(INDescriptor::class);
                $data = $provider->persistFlush($need, $data);
                if ($data['status'] === 'success') {
                    $form = $this->createForm(INDescriptorType::class, $need, ['action' => $this->generateUrl('school_admin__in_edit', ['need' => $need->getId()])]);
                    if ($id !== $need->getId()) {
                        $data['redirect'] = $this->generateUrl('school_admin__in_edit', ['need' => $need->getId()]);
                        $data['status'] = 'redirect';
                    }
                }
            } else {
                $data = ErrorMessageHelper::getInvalidInputsMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->setReturnRoute($this->generateUrl('school_admin__in_manage', ['tabName' => 'Descriptors']))->singlePanel($form->createView());

        return $pageManager->createBreadcrumbs($need->getId() > 0 ? 'Edit Individual Need' : 'Add Individual Need',
            [
                ['uri' => 'school_admin__in_manage', 'name' => 'Individual Needs']
            ]
        )
            ->render(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * delete
     * @param INDescriptor $need
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/individual/needs/{need}/delete/", name="in_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(INDescriptor $need, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(INDescriptor::class);

        $provider->delete($need);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__in_manage');
    }
}