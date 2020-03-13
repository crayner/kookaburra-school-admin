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
 * Date: 10/01/2020
 * Time: 07:50
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Twig\PageHeader;
use App\Util\ErrorMessageHelper;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\Scale;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;
use Kookaburra\SchoolAdmin\Form\ScaleType;
use Kookaburra\SchoolAdmin\Pagination\ScaleGradePagination;
use Kookaburra\SchoolAdmin\Pagination\ScalePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ScaleController
 * @package Kookaburra\SchoolAdmin\Controller
 * @todo Copy scale / scalegrade data to installation/demo files as required
 */
class ScaleController extends AbstractController
{
    /**
     * manage
     * @Route("/scale/manage/", name="scale_manage")
     * @IsGranted("ROLE_ROUTE")
     * @param ScalePagination $pagination
     * @param PageManager $pageManager
     * @return JsonResponse|Response
     */
    public function manage(ScalePagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();

        $content = ProviderFactory::getRepository(Scale::class)->findBy([], ['name' => 'ASC']);
        $pagination->setContent($content)->setAddElementRoute($this->generateUrl('school_admin__scale_add'))
            ->setPaginationScript();

        return $pageManager->createBreadcrumbs('Manage Scales', [])
            ->render(['pagination' => $pagination->toArray()]);
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param ScaleGradePagination $pagination
     * @param Scale|null $scale
     * @param string $tabName
     * @return JsonResponse|Response
     * @Route("/scale/{scale}/edit/{tabName}", name="scale_edit")
     * @Route("/scale/add/{tabName}", name="scale_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ScaleGradePagination $pagination, ?Scale $scale = null, string $tabName = 'Details')
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        $pageHeader = new PageHeader($scale ? TranslationsHelper::translate('Edit Scale ({name})', ['{name}' => $scale->getName()], 'SchoolAdmin') : TranslationsHelper::translate('Add Scale', [], 'SchoolAdmin'));
        $pageHeader->setReturnRoute($this->generateUrl('school_admin__scale_manage'));

        $pageManager->setPageHeader($pageHeader);

        if (!$scale instanceof Scale) {
            $scale = new Scale();
            $action = $this->generateUrl('school_admin__scale_add', ['tabName' => $tabName]);
        } else {
            $action = $this->generateUrl('school_admin__scale_edit', ['scale' => $scale->getId(), 'tabName' => $tabName]);
        }

        $form = $this->createForm(ScaleType::class, $scale, ['action' => $action]);

        if ($request->getContent() !== '') {
            $content = json_decode($request->getContent(), true);
            $id = $scale->getId();
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $provider = ProviderFactory::create(Scale::class);
                $data = $provider->persistFlush($scale, $data);
                if ($data['status'] === 'success') {
                    $form = $this->createForm(ScaleType::class, $scale, ['action' => $this->generateUrl('school_admin__scale_edit', ['scale' => $scale->getId(), 'tabName' => $tabName])]);
                    if ($id !== $scale->getId()) {
                        $data['redirect'] = $this->generateUrl('school_admin__scale_edit', ['scale' => $scale->getId(), 'tabName' => $tabName]);
                        $data['status'] = 'redirect';
                        ErrorMessageHelper::convertToFlash($data, $request->getSession()->getBag('flashes'));
                    }
                }
            } else {
                $data = ErrorMessageHelper::getInvalidInputsMessage($data);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer();


            return new JsonResponse($data, 200);
        }

        $container = new Container();
        $container->setSelectedPanel($tabName);

        $panel = new Panel('Details', 'SchoolAdmin');
        $container->addForm('Details', $form->createView())->addPanel($panel);
        if ($scale->getId() > 0) {
            $panel = new Panel('Grades', 'SchoolAdmin');
            $content = ProviderFactory::getRepository(ScaleGrade::class)->findBy(['scale' => $scale], ['sequenceNumber' => 'ASC']);
            $pagination->setContent($content)->setAddElementRoute($this->generateUrl('school_admin__scale_grade_add', ['scale' => $scale->getId()]));

            $panel->setPagination($pagination);
            $container->addPanel($panel);
        }

        $manager->addContainer($container)->buildContainers();

        return $pageManager->createBreadcrumbs([$scale->getId() > 0 ? 'Edit Scale ({name})' : 'Add Scale', ['{name}' => $scale->getName()]],
            [
                ['uri' => 'school_admin__scale_manage', 'name' => 'Manage Scales']
            ]
        )
            ->render(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * delete
     * @param Scale|null $scale
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/scale/{scale}/delete/", name="scale_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(Scale $scale, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(Scale::class);

        $provider->delete($scale);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__scale_manage');
    }
}