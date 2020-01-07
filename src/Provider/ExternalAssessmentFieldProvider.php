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
 * Time: 15:33
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use App\Provider\ProviderFactory;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentStudentEntry;

/**
 * Class ExternalAssessmentFieldProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class ExternalAssessmentFieldProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = ExternalAssessmentField::class;

    /**
     * canDelete
     * @param ExternalAssessmentField $field
     * @return bool
     */
    public function canDelete(ExternalAssessmentField $field): bool
    {
        if (ProviderFactory::getRepository(ExternalAssessmentStudentEntry::class)->countByField($field) === 0)
        {
            return true;
        }
        return false;
    }
}