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

use Kookaburra\SchoolAdmin\Entity\Facility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class FacilityRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class FacilityRepository extends ServiceEntityRepository
{
    /**
     * FacilityRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facility::class);
    }

    /**
     * findAllIn
     * @param array $spaces
     * @return array
     */
    public function findAllIn(array $spaces): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.id IN (:spaces)')
            ->setParameter('spaces', $spaces, Connection::PARAM_INT_ARRAY)
            ->orderBy('f.name')
            ->getQuery()
            ->getResult();
    }
}
