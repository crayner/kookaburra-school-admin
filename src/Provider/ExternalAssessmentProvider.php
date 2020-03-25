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
 * Date: 6/01/2020
 * Time: 13:45
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessment;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;

/**
 * Class ExternalAssessmentProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class ExternalAssessmentProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = ExternalAssessment::class;

    /**
     * canDelete
     * @param ExternalAssessment $assessment
     * @return bool
     */
    public function canDelete(ExternalAssessment $assessment): bool
    {
        if ($assessment->isActive())
            return false;
        if ($this->getRepository(ExternalAssessmentField::class)->countFieldOfAssessment($assessment) === 0)
        {
            return true;
        }
        return false;
    }
}