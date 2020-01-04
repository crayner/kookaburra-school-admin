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
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Entity\Facility;
use Kookaburra\SchoolAdmin\Form\FacilitySettingsType;
use Kookaburra\SchoolAdmin\Form\FacilityType;
use Kookaburra\SchoolAdmin\Pagination\FacilityPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     */
    public function manage(FacilityPagination $pagination)
    {
        $content = ProviderFactory::getRepository(Facility::class)->findBy([], ['name' => 'ASC']);
        $pagination->setContent($content)
            ->setPaginationScript();
        return $this->render('@KookaburraSchoolAdmin/facility/manage.html.twig');
    }

    /**
     * edit
     * @param ContainerManager $manager
     * @param Request $request
     * @param Facility|null $facility
     * @Route("/facility/{facility}/edit/", name="facility_edit")
     * @Route("/facility/add/", name="facility_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(ContainerManager $manager, Request $request, ?Facility $facility = null)
    {
        if (!$facility instanceof Facility) {
            $facility = new Facility();
            $action = $this->generateUrl('school_admin__facility_add');
        } else {
            $action = $this->generateUrl('school_admin__facility_edit', ['facility' => $facility->getId()]);
        }

        $form = $this->createForm(FacilityType::class, $facility, ['action' => $action]);

        if ($request->getContentType() === 'json') {
            $content = json_decode($request->getContent(), true);
            dump($content);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $facility->getId();
                $provider = ProviderFactory::create(Facility::class);
                $data = $provider->persistFlush($facility, $data);
                if ($data['status'] === 'success')
                    $form = $this->createForm(FacilityType::class, $facility, ['action' => $this->generateUrl('school_admin__facility_edit', ['facility' => $facility->getId()])]);
            } else {
                $data = ErrorMessageHelper::getInvalidInputsMessage($data);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }
        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/facility/edit.html.twig',
            [
                'facility' => $facility,
            ]
        );
    }

    /**
     * delete
     * @param Facility $facility
     * @param FlashBagInterface $flashBag
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/facility/{facility}/delete/", name="facility_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(Facility $facility, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $provider = ProviderFactory::create(Facility::class);

        $provider->delete($facility);

        $provider->getMessageManager()->pushToFlash($flashBag, $translator);

        return $this->redirectToRoute('school_admin__facility_manage');
    }

    /**
     * settings
     * @param Request $request
     * @param ContainerManager $manager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/facility/settings/", name="facility_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(Request $request, ContainerManager $manager, TranslatorInterface $translator)
    {
        // System Settings
        $form = $this->createForm(FacilitySettingsType::class, null, ['action' => $this->generateUrl('school_admin__facility_settings',)]);

        if ($request->getContentType() === 'json') {
            $data = [];
            $data['status'] = 'success';
            try {
                $data['errors'] = ProviderFactory::create(Setting::class)->handleSettingsForm($form, $request, $translator);
            } catch (\Exception $e) {
                $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }

        $manager->singlePanel($form->createView());

        return $this->render('@KookaburraSchoolAdmin/facility/settings.html.twig');
    }
}