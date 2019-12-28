<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/12/2019
 * Time: 17:29
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
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
}