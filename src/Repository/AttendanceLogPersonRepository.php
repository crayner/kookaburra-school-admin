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

use Kookaburra\SchoolAdmin\Entity\AttendanceLogPerson;
use App\Entity\CourseClass;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\RollGroups\Entity\RollGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AttendanceLogPersonRepository
 * @package Kookaburra\SchoolAdmin\Repository
 */
class AttendanceLogPersonRepository extends ServiceEntityRepository
{
    /**
     * AttendanceLogPersonRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttendanceLogPerson::class);
    }

    /**
     * findClassStudents
     * @param CourseClass $class
     * @param \DateTime $date
     * @return array
     */
    public function findClassStudents(CourseClass $class, \DateTime $date): array
    {
        $result = $this->createQueryBuilder('alp')
            ->select('alp, p')
            ->join('alp.person', 'p')
            ->where('alp.courseClass = :class')
            ->setParameter('class', $class)
            ->andWhere('alp.date = :currentDate')
            ->setParameter('currentDate', $date)
            ->andWhere('alp.context = :context')
            ->setParameter('context', 'Class')
            ->getQuery()
            ->getResult() ?: [];
        return $this->defineStudentListKeys($result);

    }

    /**
     * findRollStudents
     * @param \DateTime $date
     * @return array
     */
    public function findRollStudents(\DateTime $date): array
    {
        $result = $this->createQueryBuilder('alp')
            ->select('alp, p')
            ->join('alp.person', 'p')
            ->where('alp.date = :currentDate')
            ->setParameter('currentDate', $date)
            ->andWhere('alp.context = :context')
            ->setParameter('context', 'Roll Group')
            ->getQuery()
            ->getResult() ?: [];
        return $this->defineStudentListKeys($result);
    }

    /**
     * defineStudentListKeys
     * @param array $result
     * @return array
     */
    private function defineStudentListKeys(array $result): array
    {
        $students = [];
        foreach($result as $q=>$w)
        {
            $students[$w->getPerson()->getId()] = $w;
        }
        return $students;
    }

    /**
     * findByDateStudent
     * @param Person $person
     * @param string $showDate
     * @return array
     */
    public function findByDateStudent(Person $person, string $showDate): array
    {
        return $this->createQueryBuilder('alp')
            ->leftJoin('alp.studentEnrolment', 'se', 'WITH', 'alp.person = se.person')
            ->where('alp.person = :person')
            ->setParameter('person', $person)
            ->andWhere('alp.date = :showDate')
            ->setParameter('showDate', $showDate)
            ->getQuery()
            ->getResult();
    }
}
