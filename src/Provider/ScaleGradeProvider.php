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
 * Time: 07:59
 */

namespace Kookaburra\SchoolAdmin\Provider;


use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;

class ScaleGradeProvider implements EntityProviderInterface
{
    use EntityTrait;

    private $entityName = ScaleGrade::class;
}