<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 24/12/2019
 * Time: 17:29
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Entity\AcademicYearSpecialDay;

/**
 * Class AcademicYearSpecialDayProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class AcademicYearSpecialDayProvider implements EntityProviderInterface
{
    use EntityTrait;
    /**
     * @var string
     */
    private $entityName = AcademicYearSpecialDay::class;

    /**
     * dateExists
     * @param \DateTimeImmutable $date
     * @param AcademicYear $year
     * @return bool
     */
    public function dateExists(\DateTimeImmutable $date, AcademicYear $year): bool
    {
        return $this->getRepository()->findOneBy(['date' => $date, 'academicYear' => $year]) !== null;
    }
}