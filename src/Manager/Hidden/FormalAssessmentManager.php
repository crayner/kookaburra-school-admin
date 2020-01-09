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
 * Date: 9/01/2020
 * Time: 13:38
 */

namespace Kookaburra\SchoolAdmin\Manager\Hidden;

use App\Entity\Setting;
use App\Provider\ProviderFactory;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessment;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Entity\YearGroup;

/**
 * Class FormalAssessmentManager
 * @package Kookaburra\SchoolAdmin\Manager\Hidden
 */
class FormalAssessmentManager
{
    /**
     * createFormContent
     * @return array
     */
    public function createFormContent(): array
    {
        $years = ProviderFactory::getRepository(YearGroup::class)->findBy([], ['sequenceNumber' => 'ASC']);

        $formalAssessments = unserialize(ProviderFactory::create(Setting::class)->getSettingByScopeAsString('School Admin', 'primaryExternalAssessmentByYearGroup'));

        $result = [];
        foreach($years as $year)
        {
            foreach($formalAssessments as $q=>$w)
            {
                $found = false;
                if (isset($w['yearGroup']) && intval($w['yearGroup']) === intval($year->getId())) {
                    $found = true;
                    break;
                }
                if (!$found) {
                    $w = [];
                    $w['yearGroup'] = $year->getId();
                    $w['externalAssessment'] = null;
                    $w['category'] = null;
                    $found = true;
                }
            }
            $result[] = $w;
        }

        $formalAssessments = $result;

        $ea = ProviderFactory::getRepository(ExternalAssessmentField::class)->findByCategoryAssessment();

        $choices = [];
        $fieldSets = [];
        foreach ($ea as $item)
        {
            $choices[$item->getExternalAssessment()->getId()] = $item->getExternalAssessment()->getName();
            $fieldSets[$item->getExternalAssessment()->getId()] = $item->getExternalAssessment()->getFieldChoices();
        }

        dump($formalAssessments);
        return ['years' => $years,'data' => $formalAssessments, 'choices' => $choices, 'fieldChoices' => $fieldSets, 'jsonFieldChoices' => json_encode($fieldSets)];
    }
}