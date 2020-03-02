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
 * Date: 4/01/2020
 * Time: 17:29
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\Activities\Entity\ActivitySlot;
use Kookaburra\RollGroups\Entity\RollGroup;
use Kookaburra\SchoolAdmin\Entity\Facility;
use Kookaburra\SchoolAdmin\Entity\FacilityPerson;

/**
 * Class FacilityProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class FacilityProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Facility::class;

    /**
     * canDelete
     * @param Facility $facility
     * @return bool
     */
    public function canDelete(Facility $facility): bool
    {
        if ($this->getRepository(RollGroup::class)->countFacility($facility) > 0)
            return false;
        if ($this->getRepository(ActivitySlot::class)->countFacility($facility) > 0)
            return false;
        if ($this->getRepository(FacilityPerson::class)->countFacility($facility) > 0)
            return false;
        return true;
    }
}