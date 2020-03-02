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
 * Date: 16/01/2020
 * Time: 17:15
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\FileExtension;
use Kookaburra\SchoolAdmin\Form\FileExtensionType;
use Kookaburra\SchoolAdmin\Pagination\FileExtensionPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FileExtensionController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class FileExtensionController extends AbstractController
{
    /**
     * manage
     * @param FileExtensionPagination $pagination
     * @param PageManager $pageManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/file/extensions/manage/", name="file_extensions_manage")
     * @IsGranted("ROLE_ROUTE")
     */
    public function manage(FileExtensionPagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $content = ProviderFactory::getRepository(FileExtension::class)->findBy([], ['extension' => 'ASC']);
        $pagination->setContent($content)
            ->setAddElementRoute($this->generateUrl('school_admin__file_extensions_add'))
            ->setPaginationScript();
        return $pageManager->createBreadcrumbs('File Extensions')
            ->render(['pagination' => $pagination->toArray()]);
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param FileExtension|null $fileExtension
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/file/extensions/{fileExtension}/edit/", name="file_extensions_edit")
     * @Route("/file/extensions/add/", name="file_extensions_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ?FileExtension $fileExtension = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();
        if (!$fileExtension instanceof FileExtension) {
            $fileExtension = new FileExtension();
            $action = $this->generateUrl('school_admin__file_extensions_add');
        } else {
            $action = $this->generateUrl('school_admin__file_extensions_edit', ['fileExtension' => $fileExtension->getId()]);
        }

        $form = $this->createForm(FileExtensionType::class, $fileExtension, ['action' => $action]);

        if ($request->getContent() !== '') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $fileExtension->getId();
                $provider = ProviderFactory::create(FileExtension::class);
                $data = $provider->persistFlush($fileExtension, $data);
                if ($data['status'] === 'success')
                    $form = $this->createForm(FileExtensionType::class, $fileExtension, ['action' => $this->generateUrl('school_admin__file_extensions_edit', ['fileExtension' => $fileExtension->getId()])]);
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->setAddElementRoute($this->generateUrl('school_admin__file_extensions_add'))
            ->setReturnRoute($this->generateUrl('school_admin__file_extensions_manage'))
            ->singlePanel($form->createView());

        return $pageManager->createBreadcrumbs($fileExtension->getId() > 0 ? 'Edit File Extension' : 'Add File Extension',
            [
                ['uri' => 'school_admin__file_extensions_manage', 'name' => 'File Extensions']
            ]
        )
            ->render(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * delete
     * @param FileExtension $fileExtension
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/file/extensions/{fileExtension}/delete/", name="file_extensions_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(FileExtension $fileExtension, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(FileExtension::class);

        $provider->delete($fileExtension);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__file_extensions_manage');
    }
}