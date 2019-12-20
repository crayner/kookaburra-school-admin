<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 21/12/2019
 * Time: 07:45
 */
namespace Kookaburra\SchoolAdmin;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class KookaburraSchoolAdminBundle
 * @package Kookaburra\SchoolAdmin
 */
class KookaburraSchoolAdminBundle extends Bundle
{
    /**
     * build
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}