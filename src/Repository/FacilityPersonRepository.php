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

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Kookaburra\SchoolAdmin\Entity\Facility;
use Kookaburra\SchoolAdmin\Entity\FacilityPerson;

/**
 * Class FacilityPersonRepository
 * @package App\Repository
 */
class FacilityPersonRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacilityPerson::class);
    }

    /**
     * countFacility
     * @param Facility $facility
     * @return int
     */
    public function countFacility(Facility $facility): int
    {
        try {
            return intval($this->createQueryBuilder('e')
                ->select('COUNT(e.id)')
                ->where('e.facility = :facility')
                ->setParameter('facility', $facility)
                ->getQuery()
                ->getSingleScalarResult());
        } catch ( NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }
}
