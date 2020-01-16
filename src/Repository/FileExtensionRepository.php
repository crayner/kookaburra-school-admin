<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */

namespace Kookaburra\SchoolAdmin\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kookaburra\SchoolAdmin\Entity\FileExtension;

/**
 * Class FileExtensionRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class FileExtensionRepository extends ServiceEntityRepository
{
    /**
     * FileExtensionRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileExtension::class);
    }
}
