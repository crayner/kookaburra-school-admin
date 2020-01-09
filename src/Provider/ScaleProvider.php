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
 * Date: 10/01/2020
 * Time: 07:58
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\Scale;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;

/**
 * Class ScaleProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class ScaleProvider implements EntityProviderInterface
{
    use EntityTrait;

    private $entityName = Scale::class;

    public function canDelete(Scale $scale)
    {
        if ($this->getRepository(ScaleGrade::class)->countScaleUse($scale) === 0)
            return true;
        return false;
    }
}