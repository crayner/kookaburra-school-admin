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

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Entity\Setting;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Form\ExternalDataPointsType;
use Kookaburra\SchoolAdmin\Form\InternalDataPointsType;
use Kookaburra\SchoolAdmin\Manager\Hidden\DataPoints;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrackingController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class TrackingController extends AbstractController
{
    /**
     * settings
     * @param PageManager $pageManager
     * @param ContainerManager $manager
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/tracking/settings/{tabName}", name="tracking_settings")
     * @IsGranted("ROLE_ROUTE")
     */
    public function settings(PageManager $pageManager, ContainerManager $manager, string $tabName = 'External')
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        // System Settings
        $dpe = new DataPoints();
        $dpi = new DataPoints();
        $fields = ProviderFactory::create(ExternalAssessmentField::class)->findByActiveInEAOrder();

        $internal = [];
        $internal['names'] = ProviderFactory::create(Setting::class)->getSettingByScopeAsArray('Formal Assessment', 'internalAssessmentTypes');
        $internal['tracking'] = ProviderFactory::create(Setting::class)->getSettingByScopeAsArray('Tracking', 'internalAssessmentDataPoints');

        $dpe->convertExternal($fields);
        $dpi->convertInternal($internal);

        $eForm = $this->createForm(ExternalDataPointsType::class, $dpe);
        $iForm = $this->createForm(InternalDataPointsType::class, $dpi);

        if ($request->getMethod() === 'POST') {
            $content = json_decode($request->getContent(), true);

            if (key_exists('ext-header', $content)) {
                $eForm->submit($content);
                if ($eForm->isValid()) {
                    $dpe->handleExternalRequest($content);
                    $eForm = $this->createForm(ExternalDataPointsType::class, $dpe);
                    $manager->singlePanel($eForm->createView());

                    return new JsonResponse([
                        'form' => $manager->getFormFromContainer(),
                        'status' => 'success',
                        'errors' => [['class' => 'success', 'message' => TranslationsHelper::translate('return.success.0', [], 'messages')]],
                    ]);
                }
            }


            if (key_exists('int-header', $content)) {
                $iForm->submit($content);
                if ($iForm->isValid()) {
                    $dpi->handleInternalRequest($content);
                    $iForm = $this->createForm(InternalDataPointsType::class, $dpi);
                    $manager->singlePanel($iForm->createView());

                    return new JsonResponse([
                        'form' => $manager->getFormFromContainer(),
                        'status' => 'success',
                        'errors' => [['class' => 'success', 'message' => TranslationsHelper::translate('return.success.0', [], 'messages')]],
                    ]);
                }
            }

        }
        $container = new Container();
        $manager->setTranslationDomain('SchoolAdmin');

        $panel = new Panel();
        $panel->setName('External');
        $container->addForm('External', $eForm->createView())->addPanel($panel);

        $panel = new Panel();
        $panel->setName('Internal');

        $container->addForm('Internal', $iForm->createView())->addPanel($panel)->setSelectedPanel($tabName);
        $manager->addContainer($container->addPanel($panel))->buildContainers();

        return $pageManager->createBreadcrumbs('Tracking Settings')
            ->render(['containers' => $manager->getBuiltContainers()]);
    }
}