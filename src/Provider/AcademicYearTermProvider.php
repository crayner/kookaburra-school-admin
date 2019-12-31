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
 * Date: 21/12/2019
 * Time: 20:42
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\AcademicYearTerm;

/**
 * Class AcademicYearTermProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class AcademicYearTermProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = AcademicYearTerm::class;
}