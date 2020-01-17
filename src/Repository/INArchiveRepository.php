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
use Kookaburra\SchoolAdmin\Entity\INArchive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kookaburra\SchoolAdmin\Entity\INDescriptor;

/**
 * Class INArchiveRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class INArchiveRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, INArchive::class);
    }

    /**
     * countDescriptor
     * @param INDescriptor $descriptor
     * @return int
     */
    public function countDescriptor(INDescriptor $descriptor): int
    {
        try {
            return intval($this->createQueryBuilder('a')
                ->join('a.descriptors', 'd')
                ->where('d.id = :descriptor')
                ->setParameter('descriptor', $descriptor->getId())
                ->select(['COUNT(a.id)'])
                ->getQuery()
                ->getSingleScalarResult());
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }
}
