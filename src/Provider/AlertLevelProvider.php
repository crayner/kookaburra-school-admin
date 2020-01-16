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
 * Date: 16/01/2020
 * Time: 15:39
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\AlertLevel;

/**
 * Class AlertLevelProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class AlertLevelProvider implements EntityProviderInterface
{
    use EntityTrait;
    /**
     * @var string
     */
    private $entityName = AlertLevel::class;

    /**
     * canDelete
     * @return bool
     */
    public function canDelete(): bool
    {
        return false;
    }
}