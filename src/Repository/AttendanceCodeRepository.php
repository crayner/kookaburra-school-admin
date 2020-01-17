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

use Kookaburra\SchoolAdmin\Entity\AttendanceCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AttendanceCodeRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class AttendanceCodeRepository extends ServiceEntityRepository
{
    /**
     * AttendanceCodeRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttendanceCode::class);
    }

    /**
     * findActive
     * @return array
     */
    public function findActive(bool $asArray = false): array
    {
        $query = $this->createQueryBuilder('a', 'a.id')
            ->where('a.active = :yes')
            ->setParameter('yes', 'Y')
            ->orderBy('a.sequenceNumber', 'ASC')
            ->getQuery();
        if ($asArray)
            return $query->getArrayResult();
        return $query->getResult();
    }

    /**
     * findDefaultAttendanceCode
     * @return AttendanceCode|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findDefaultAttendanceCode(): ?AttendanceCode
    {
        return $this->createQueryBuilder('ac')
            ->orderBy('ac.sequenceNumber', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * findAttendanceTypeList
     * @return array
     */
    public function findAttendanceTypeList():array
    {
        return $this->createQueryBuilder('c')
            ->select(['c.name'])
            ->orderBy('c.sequenceNumber', 'ASC')
            ->getQuery()
            ->getResult();

    }
}
