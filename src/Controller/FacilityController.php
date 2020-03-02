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
 * Date: 4/01/2020
 * Time: 14:16
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Entity\Setting;
use App\Manager\MessageManager;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Entity\Facility;
use Kookaburra\SchoolAdmin\Form\FacilitySettingsType;
use Kookaburra\SchoolAdmin\Form\FacilityType;
use Kookaburra\SchoolAdmin\Pagination\FacilityPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FacilityController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class FacilityController extends AbstractController
{
    /**
     * manage
     * @Route("/facility/manage/", name="facility_manage")
     * @IsGranted("ROLE_ROUTE")
     * @param FacilityPagination $pagination
     * @param PageManager $pageManager
     * @return JsonResponse|Response
     */
    public function manage(FacilityPagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $content = ProviderFactory::getRepository(Facility::class)->findBy([], ['name' => 'ASC']);
        $pagination->setContent($content)->setAddElementRoute($this->generateUrl('school_admin__facility_add'))
            ->setPaginationScript();
        return $pageManager->createBreadcrumbs('Facilities')
            ->render(['pagination' => $pagination->toArray()]);
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param PageManager $pageManager
     * @param Facility|null $facility
     * @return JsonResponse|Response
     * @Route("/facility/{facility}/edit/", name="facility_edit")
     * @Route("/facility/add/", name="facility_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, PageManager $pageManager, ?Facility $facility = null)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        if (!$facility instanceof Facility) {
            $facility = new Facility();
            $action = $this->generateUrl('school_admin__facility_add');
        } else {
            $action = $this->generateUrl('school_admin__facility_edit', ['facility' => $facility->getId()]);
        }

        $form = $this->createForm(FacilityType::class, $facility, ['action' => $action, 'facility_setting_uri' => $this->generateUrl('school_admin__facility_settings')]);

        if ($request->getContent() !== '') {
            $content = json_decode($request->getContent(), true);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $facility->getId();
                $provider = ProviderFactory::create(Facility::class);
                $data = $provider->persistFlush($facility, $data);
                if ($data['status'] === 'success' && $id === $facility->getId())
                    $form = $this->createForm(FacilityType::class, $facility, ['action' => $this->generateUrl('school_admin__facility_edit', ['facility' => $facility->getId()]), 'facility_setting_uri' => $this->generateUrl('school_admin__facility_settings')]);
            } else {
                $data = ErrorMessageHelper::getInvalidInputsMessage($data);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->setReturnRoute($this->generateUrl('school_admin__facility_manage'))->setAddElementRoute($this->generateUrl('school_admin__facility_add'))->singlePanel($form->createView());

        return $pageManager->createBreadcrumbs($facility->getId() > 0 ? 'Edit Facility' : 'Add Facility',
            [
                ['uri' => 'school_admin__facility_manage', 'name' => 'Facilities']
            ]
        )
            ->render(['containers' => $manager->getBuiltContainers()]);
    }

    /**
     * delete
     * @param Facility $facility
     * @param TranslatorInterface $translator
     * @param FacilityPagination $pagination
     * @param PageManager $pageManager
     * @Route("/facility/{facility}/delete/", name="facility_delete")
     * @IsGranted("ROLE_ROUTE")
     * @return JsonResponse|Response
     */
    public function delete(Facility $facility, TranslatorInterface $translator, FacilityPagination $pagination, PageManager $pageManager)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $provider = ProviderFactory::create(Facility::class);

        $provider->delete($facility);

        $content = ProviderFactory::getRepository(Facility::class)->findBy([], ['name' => 'ASC']);

        $pagination->setContent($content)->setAddElementRoute($this->generateUrl('school_admin__facility_add'))
            ->setPaginationScript();
        return $pageManager->createBreadcrumbs('Facilities')
            ->render(['pagination' => $pagination->toArray(), 'messages' => $provider->getMessageManager()->serialiseTranslatedMessages($translator), 'url' => $this->generateUrl('school_admin__facility_manage')]);
    }

    /**
     * settings
     * @param Request $request
     * @param ContainerManager $manager
     * @return JsonResponse|Response
     * @Route("/facility/settings/", name="facility_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(PageManager $pageManager, ContainerManager $manager, TranslatorInterface $translator)
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();
        // System Settings
        $form = $this->createForm(FacilitySettingsType::class, null, ['action' => $this->generateUrl('school_admin__facility_settings',)]);

        if ($request->getContent() !== '') {
            $data = [];
            $data['status'] = 'success';
            try {
                $data['errors'] = ProviderFactory::create(Setting::class)->handleSettingsForm($form, $request, $translator);
                if ($data['status'] === 'success')
                    $form = $this->createForm(FacilitySettingsType::class, null, ['action' => $this->generateUrl('school_admin__facility_settings',)]);
            } catch (\Exception $e) {
                $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $manager->singlePanel($form->createView());

        return $pageManager->createBreadcrumbs('Facility Settings')
            ->render(['containers' => $manager->getBuiltContainers()]);
    }
}