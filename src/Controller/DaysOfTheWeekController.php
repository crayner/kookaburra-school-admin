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
 * Date: 22/12/2019
 * Time: 17:56
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\DaysOfWeek;
use Kookaburra\SchoolAdmin\Form\DayOfTheWeekType;
use Kookaburra\SystemAdmin\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DaysOfTheWeekController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class DaysOfTheWeekController extends AbstractController
{
    /**
     * manage
     * @param PageManager $pageManager
     * @param ContainerManager $manager
     * @param string $tabName
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/days/of/the/week/{tabName}", name="days_of_the_week")
     */
    public function manage(PageManager $pageManager, ContainerManager $manager, string $tabName = 'Monday')
    {
        if ($pageManager->isNotReadyForJSON()) return $pageManager->getBaseResponse();
        $request = $pageManager->getRequest();

        $container = new Container();
        $container->setTarget('formContent')->setSelectedPanel($tabName);
        TranslationsHelper::setDomain('SchoolAdmin');

        foreach(ProviderFactory::getRepository(DaysOfWeek::class)->findBy([],['sequenceNumber' => 'ASC']) as $day)
        {
            $form = $this->createForm(DayOfTheWeekType::class, $day, ['action' => $this->generateUrl('school_admin__days_of_the_week', ['tabName' => $day->getName()])]);
            $panel = new Panel($day->getName(), 'SchoolAdmin');
            $container->addForm($day->getName(), $form->createView())->addPanel($panel);
        }

        if ($request->getContent() !== '')
        {
            $content = json_decode($request->getContent(), true);
            $day = $content['id'] > 0 ? ProviderFactory::getRepository(DaysOfWeek::class)->find($content['id']) : new DaysOfWeek();
            $form = $this->createForm(DayOfTheWeekType::class, $day, ['action' => $this->generateUrl('school_admin__days_of_the_week', ['tabName' => $tabName])]);
            $form->submit($content);
            $data = [];
            $data['status'] = 'success';
            if ($form->isValid()) {
                $id = $day->getId();
                $provider = ProviderFactory::create(Role::class);
                $data = $provider->persistFlush($day, $data);
                if ($id !== $day->getId() && $data['status'] === 'success')
                    $form = $this->createForm(DayOfTheWeekType::class, $day, ['action' => $this->generateUrl('school_admin__days_of_the_week', ['tabName' => $tabName])]);
            } else {
                $data = ErrorMessageHelper::getInvalidInputsMessage($data);
            }

            $manager->singlePanel($form->createView());
            $data['form'] = $manager->getFormFromContainer('formContent', 'single');

            return new JsonResponse($data, 200);
        }


        $manager->addContainer($container)->buildContainers();
        $pageManager->createBreadcrumbs('Manage Days of the Week', []);

        return $pageManager->render(['containers' => $manager->getBuiltContainers()]);
    }
}