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

use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ExternalAssessmentFieldRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class ExternalAssessmentFieldRepository extends ServiceEntityRepository
{
    /**
     * ExternalAssessmentFieldRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalAssessmentField::class);
    }

    /**
     * findByActiveInEAOrder
     * @return array
     */
    public function findByActiveInEAOrder(): array
    {
        return $this->createQueryBuilder('f')
            ->distinct()
            ->select(['a.id','a.nameShort','f.category','f.yearGroupList'])
            ->leftJoin('f.externalAssessment', 'a')
            ->orderBy('a.nameShort', 'ASC')
            ->addOrderBy('f.category', 'ASC')
            ->where('a.active = :true')
            ->setParameter('true', 'Y')
            ->getQuery()
            ->getResult();
    }
}
