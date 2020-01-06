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
 * Date: 5/01/2020
 * Time: 08:55
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\ContainerManager;
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Kookaburra\SchoolAdmin\Form\TrackingSettingsType;
use Kookaburra\SchoolAdmin\Manager\Hidden\TrackingSettings;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TrackingController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class TrackingController extends AbstractController
{
    /**
     * settings
     * @param Request $request
     * @param ContainerManager $manager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/tracking/settings/", name="tracking_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(Request $request, ContainerManager $manager, TranslatorInterface $translator)
    {
        // System Settings
        $et = new TrackingSettings();
        $fields = ProviderFactory::getRepository(ExternalAssessmentField::class)->findByActiveInEAOrder();

        $internal = ProviderFactory::create(Setting::class)->getSettingByScopeAsArray('Formal Assessment', 'internalAssessmentTypes');

        $et->setExternal(new ArrayCollection($fields))->setInternal(new ArrayCollection($internal));

        $form = $this->createForm(TrackingSettingsType::class, $et , ['action' => $this->generateUrl('school_admin__tracking_settings',)]);

        $form->handleRequest($request);



        return $this->render('@KookaburraSchoolAdmin/tracking/settings.html.twig',
            [
                'form' => $form->createView(),
                'years' => ProviderFactory::getRepository(YearGroup::class)->findBy([], ['sequenceNumber' => 'ASC']),
            ]
        );
    }

}