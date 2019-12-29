<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/12/2019
 * Time: 14:29
 */

namespace Kookaburra\SchoolAdmin\Manager;

use App\Provider\ProviderFactory;
use Kookaburra\SchoolAdmin\Entity\AcademicYearSpecialDay;
use Kookaburra\SchoolAdmin\Util\AcademicYearHelper;

/**
 * Class SpecialDayManager
 * @package Kookaburra\SchoolAdmin
 */
class SpecialDayManager
{
    /**
     * canDuplicate
     * @param AcademicYearSpecialDay $specialDay
     * @return bool
     * @throws \Exception
     */
    public static function canDuplicate(AcademicYearSpecialDay $specialDay): bool
    {
        $nextYear = AcademicYearHelper::getNextAcademicYear($specialDay->getAcademicYear());
        if (null === $nextYear) return false;

        return ! ProviderFactory::create(AcademicYearSpecialDay::class)->dateExists(self::getDuplicateDate($specialDay), $nextYear);
    }

    /**
     * getDuplicateDate
     * @param AcademicYearSpecialDay $specialDay
     * @return \DateTime
     * @throws \Exception
     */
    public static function getDuplicateDate(AcademicYearSpecialDay $specialDay): \DateTimeImmutable
    {
        $date = new \DateTime($specialDay->getDate()->format('Y-m-d'));
        $date->add(new \DateInterval('P1Y'));

        return new \DateTimeImmutable($date->format('Y-m-d'));
    }
}