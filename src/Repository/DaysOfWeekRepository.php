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

use Kookaburra\SchoolAdmin\Entity\DaysOfWeek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class DaysOfWeekRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class DaysOfWeekRepository extends ServiceEntityRepository
{
    /**
     * DaysOfWeekRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DaysOfWeek::class);
    }

    /**
     * @var array
     */
    private $daysOfWeek;

    /**
     * getDaysOfWeek
     * @return array
     */
    public function findAllAsArray(): array
    {
        if (! empty($this->daysOfWeek))
            return $this->daysOfWeek;
        $this->daysOfWeek = $this->createQueryBuilder('dow', 'dow.nameShort')
            ->getQuery()
            ->getArrayResult();
        return $this->daysOfWeek;
    }

    /**
     * findAllByName
     * @return array
     */
    public function findAllByName(): array
    {
        return $this->createQueryBuilder('d', 'd.name')
            ->orderBy('d.sequenceNumber')
            ->getQuery()
            ->getResult();
    }
}
