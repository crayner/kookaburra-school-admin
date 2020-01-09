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

use App\Provider\ProviderFactory;
use Kookaburra\SchoolAdmin\Entity\Scale;
use Kookaburra\SchoolAdmin\Pagination\ScalePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ScaleController extends AbstractController
{
    /**
     * manage
     * @Route("/scale/manage/", name="scale_manage")
     * @IsGranted("ROLE_ROUTE")
     */
    public function manage(ScalePagination $pagination)
    {
        $content = ProviderFactory::getRepository(Scale::class)->findBy([], ['name' => 'ASC']);
        $pagination->setContent($content)
            ->setPaginationScript();
        return $this->render('@KookaburraSchoolAdmin/scale/manage.html.twig');
    }

    /**
     * edit
     * @param Scale|null $scale
     * @Route("/scale/{scale}/edit/", name="scale_edit")
     * @Route("/scale/add/", name="scale_add")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(?Scale $scale = null)
    {

    }

    /**
     * delete
     * @param Scale|null $scale
     * @Route("/scale/{scale}/delete/", name="scale_delete")
     * @IsGranted("ROLE_ROUTE")
     */
    public function delete(Scale $scale)
    {

    }
}