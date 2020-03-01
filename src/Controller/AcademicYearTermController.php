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
 * Date: 21/12/2019
 * Time: 19:58
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Entity\AcademicYearTerm;
use Kookaburra\SchoolAdmin\Form\AcademicYearTermType;
use Kookaburra\SchoolAdmin\Form\AcademicYearType;
use Kookaburra\SchoolAdmin\Pagination\AcademicYearPagination;
use Kookaburra\SchoolAdmin\Pagination\AcademicYearTermPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AcademicYearTermController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class AcademicYearTermController extends AbstractController
{
    /**
     * manage
     * @Route("/academic/year/term/manage/", name="academic_year_term_manage")
     * @IsGranted("ROLE_ROUTE")
     * @param AcademicYearTermPagination $pagination
     * @param PageManager $pageManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manage(AcademicYearTermPagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();

        $content = ProviderFactory::getRepository(AcademicYearTerm::class)->findByPaginationList();
        $pagination->setContent($content)->setPageMax(25)
            ->setPaginationScript()->setAddElementRoute($this->generateUrl('school_admin__academic_year_term_add'));

        $pageManager->createBreadcrumbs('Manage Academic Year Terms', []);

        return $pageManager->createResponse(['pagination' => $pagination->toArray()]);
    }

    /**
     * edit
     * @Route("/academic/year/term/{term}/edit/", name="academic_year_term_edit")
     * @Route("/academic/year/term/add/", name="academic_year_term_add")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param AcademicYearTerm|null $term
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ?AcademicYearTerm $term = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        if (!$term instanceof AcademicYearTerm) {
            $term = new AcademicYearTerm();
            $action = $this->generateUrl('school_admin__academic_year_term_add');
        } else {
            $action = $this->generateUrl('school_admin__academic_year_term_edit', ['term' => $term->getId()]);
        }

        $form = $this->createForm(AcademicYearTermType::class, $term, ['action' => $action]);

        if ($request->getContent() !== '') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $term->getId();
                $provider = ProviderFactory::create(AcademicYearTerm::class);
                $data = $provider->persistFlush($term, $data);
                if ($id !== $term->getId() && $data['status'] === 'success')
                    $form = $this->createForm(AcademicYearTermType::class, $term, ['action' => $this->generateUrl('school_admin__academic_year_term_edit', ['term' => $term->getId()])]);
            } else {
                $data['errors'][] = ['class' => 'error', 'message' => TranslationsHelper::translate('return.error.1', [], 'messages')];
                $data['status'] = 'error';
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->setAddElementRoute($this->generateUrl('school_admin__academic_year_term_manage'))->setReturnRoute($this->generateUrl('school_admin__academic_year_term_manage'        ))->singlePanel($form->createView());
        $pageManager->createBreadcrumbs($term->getId() > 0 ? 'Edit Academic Year Term' : 'Add Academic Year Term', [['uri' => 'school_admin__academic_year_term_manage', 'name' => 'Manage Terms']]);

        return $pageManager->createResponse(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * delete
     * @Route("/academic/year/term/{term}/delete/", name="academic_year_term_delete")
     * @IsGranted("ROLE_ROUTE")
     * @param AcademicYearTerm $term
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(AcademicYearTerm $term, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(AcademicYearTerm::class);

        $provider->delete($term);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__academic_year_term_manage');
    }
}
