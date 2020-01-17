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

use Kookaburra\SchoolAdmin\Entity\AttendanceLogRollGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AttendanceLogRollGroupRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class AttendanceLogRollGroupRepository extends ServiceEntityRepository
{
    /**
     * AttendanceLogRollGroupRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttendanceLogRollGroup::class);
    }

    /**
     * isAttendanceTaken
     * @param int $class
     * @param \DateTime $date
     * @return bool
     */
    public function isAttendanceTaken(int $roll, \DateTime $date): bool
    {
        if (empty($this->createQueryBuilder('alrg')
            ->join('alrg.rollGroup', 'rg')
            ->where('alrg.date = :date')
            ->setParameter('date', $date)
            ->andWhere('rg.id = :rgid')
            ->setParameter('rgid', $roll)
            ->getQuery()
            ->getResult()))
            return false;
        return true;
    }
}
