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
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentStudentEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ExternalAssessmentStudentEntryRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class ExternalAssessmentStudentEntryRepository extends ServiceEntityRepository
{
    /**
     * ExternalAssessmentStudentEntryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalAssessmentStudentEntry::class);
    }

    /**
     * countByField
     * @param ExternalAssessmentField $field
     * @return int
     */
    public function countByField(ExternalAssessmentField $field): int
    {
        try {
            return $this->createQueryBuilder('e')
                ->select('COUNT(e.id)')
                ->where('e.externalAssessmentField = :field')
                ->setParameter('field', $field)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }
}
