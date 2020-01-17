<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 17/01/2020
 * Time: 14:16
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\AttendanceCode;

/**
 * Class AttendanceCodeProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class AttendanceCodeProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = AttendanceCode::class;

    /**
     * canDelete
     * @param AttendanceCode $code
     * @return bool
     */
    public function canDelete(AttendanceCode $code): bool
    {
        return !$code->isActive();
    }
}