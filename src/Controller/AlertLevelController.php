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
 * Time: 12:45
 */

namespace Kookaburra\SchoolAdmin\Controller;

use App\Container\Container;
use App\Container\ContainerManager;
use App\Container\Panel;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Entity\AlertLevel;
use Kookaburra\SchoolAdmin\Form\AlertLevelType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AlertLevelController
 * @package Kookaburra\SchoolAdmin\Controller
 */
class AlertLevelController extends AbstractController
{
    /**
     * manage
     * @Route("/alert/levels/manage/{tabName}", name="alert_levels")
     * @IsGranted("ROLE_ROUTE")
     * @param ContainerManager $manager
     * @param Request $request
     * @param string|null $tabName
     * @return Response
     */
    public function manage(ContainerManager $manager, ?string $tabName = null)
    {
        $container = new Container();
        $container->setTarget('formContent')->setSelectedPanel($tabName);

        $levels = ProviderFactory::getRepository(AlertLevel::class)->findBy([],['sequenceNumber' => 'ASC']);
        foreach($levels as $q=>$level) {
            dump($level);
            $form = $this->createForm(AlertLevelType::class, $level, ['action' => $this->generateUrl('school_admin__alert_level_change', ['level' => $level->getId()])]);
            $panel = new Panel($level->getName(), 'SchoolAdmin');
            $container->addForm($level->getName(), $form->createView())->addPanel($panel);
        }

        $manager->addContainer($container)->buildContainers();

        return $this->render('@KookaburraSchoolAdmin/alert-levels/manage.html.twig');
    }

    /**
     * manageChange
     * @param Request $request
     * @param AlertLevel $level
     * @param ContainerManager $manager
     * @Route("/alert/level/{level}/change/", name="alert_level_change", methods={"POST"})
     * @Security("is_granted('ROLE_ROUTE', ['school_admin__alert_levels'])")
     * @return JsonResponse
     */
    public function manageChange(Request $request, AlertLevel $level, ContainerManager $manager)
    {
        $form = $this->createForm(AlertLevelType::class,$level, ['action' => $this->generateUrl('school_admin__alert_level_change', ['level' => $level->getId()])]);
        $name = $level->getName();
dump($level);
        $content = json_decode($request->getContent(), true);

        $form->submit($content);
        if ($form->isValid()) {
            $data = ProviderFactory::create(AlertLevel::class)->persistFlush($level, []);
        } else {
            $data = ErrorMessageHelper::getInvalidInputsMessage([], true);
        }
        $container = new Container();
        $container->setTarget('formContent')->setSelectedPanel($name);
        $panel = new Panel($name, 'SchoolAdmin');
        $container->addForm($name, $form->createView())->addPanel($panel);
        $manager->addContainer($container)->buildContainers();
        $data['form'] = $manager->getFormFromContainer('formContent',$name);
        return new JsonResponse($data, 200);
    }
}