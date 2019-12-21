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

use Kookaburra\SchoolAdmin\Entity\AcademicYearTerm;
use Kookaburra\SchoolAdmin\Util\AcademicYearHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class AcademicYearTermRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class AcademicYearTermRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcademicYearTerm::class);
    }

    /**
     * isDayInTerm
     * @param \DateTime $date
     * @return bool
     */
    public function isDayInTerm(\DateTime $date): bool
    {
        if ($this->createQueryBuilder('syt')
                ->select('COUNT(syt)')
                ->where('syt.firstDay <= :date and syt.lastDay >= :date')
                ->andWhere('syt.AcademicYear = :AcademicYear')
                ->setParameters(['AcademicYear' => AcademicYearHelper::getCurrentAcademicYear(), 'date' => $date])
                ->getQuery()
                ->getSingleScalarResult() > 0)
            return true;
        return false;
    }

    /**
     * @param \DateTime $date
     * @return AcademicYearTerm|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByDay(\DateTime $date): ?AcademicYearTerm
    {
        return $this->createQueryBuilder('syt')
                ->where('syt.firstDay <= :date and syt.lastDay >= :date')
                ->andWhere('syt.AcademicYear = :AcademicYear')
                ->setParameters(['AcademicYear' => AcademicYearHelper::getCurrentAcademicYear(), 'date' => $date])
                ->getQuery()
                ->getOneOrNullResult();
    }
}
