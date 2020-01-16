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
     * @Route("/file/extensions/manage/", name="file_extensions_manage")
     * @IsGranted("ROLE_ROUTE")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manage(FileExtensionPagination $pagination)
    {
        $content = ProviderFactory::getRepository(FileExtension::class)->findBy([], ['extension' => 'ASC']);
        $pagination->setContent($content)
            ->setPaginationScript();
        return $this->render('@KookaburraSchoolAdmin/file-extension/manage.html.twig');
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param Request $request
     * @param FileExtension|null $fileExtension
     * @Route("/file/extensions/{fileExtension}/edit/", name="file_extensions_edit")
     * @Route("/file/extensions/add/", name="file_extensions_add")
     * @IsGranted("ROLE_ROUTE")
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(ContainerManager $manager, Request $request, ?FileExtension $fileExtension = null)
    {
        if (!$fileExtension instanceof FileExtension) {
            $fileExtension = new FileExtension();
            $action = $this->generateUrl('school_admin__file_extensions_add');
        } else {
            $action = $this->generateUrl('school_admin__file_extensions_edit', ['fileExtension' => $fileExtension->getId()]);
        }

        $form = $this->createForm(FileExtensionType::class, $fileExtension, ['action' => $action]);

        if ($request->getContentType() === 'json') {
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
        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/file-extension/edit.html.twig',
            [
                'fileExtension' => $fileExtension,
            ]
        );
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