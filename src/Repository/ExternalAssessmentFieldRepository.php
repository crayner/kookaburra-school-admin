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

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessment;
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

    /**
     * countFieldOfAssessment
     * @param ExternalAssessment $assessment
     * @return int
     */
    public function countFieldOfAssessment(ExternalAssessment $assessment): int
    {
        try {
            return $this->createQueryBuilder('e')
                ->select('COUNT(e.id)')
                ->where('e.externalAssessment = :assessment')
                ->setParameter('assessment', $assessment)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * findByAssessment
     * @param ExternalAssessment $assessment
     * @return array
     */
    public function findByAssessment(ExternalAssessment $assessment): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.externalAssessment = :assessment')
            ->setParameter('assessment', $assessment)
            ->orderBy('e.category', 'ASC')
            ->addOrderBy('e.order', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * findByCategoryAssessment
     * @return array
     */
    public function findByCategoryAssessment(): array
    {
        return $this->createQueryBuilder('f')
            ->select(['f','a','s'])
            ->join('f.externalAssessment', 'a')
            ->join('f.scale', 's')
            ->where('a.active = :true')
            ->setParameter('true', 'Y')
            ->groupBy('f.externalAssessment')
            ->addGroupBy('f.category')
            ->getQuery()
            ->getResult();
    }
}
