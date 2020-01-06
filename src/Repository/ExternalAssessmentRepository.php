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

use Kookaburra\SchoolAdmin\Entity\ExternalAssessment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ExternalAssessmentRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class ExternalAssessmentRepository extends ServiceEntityRepository
{
    /**
     * ExternalAssessmentRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalAssessment::class);
    }
}
