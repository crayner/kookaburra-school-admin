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

use App\Entity\Setting;
use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use App\Provider\ProviderFactory;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentStudentEntry;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

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

    /**
     * findByActiveInEAOrder
     * @return array
     */
    public function findByActiveInEAOrder(): array
    {
        $result = $this->getRepository()->findByActiveInEAOrder();
        $result = array_map(function($item){
            $item['label'] = $item['nameShort'] . ' - ';
            $w = explode('_', $item['category']);
            $item['name'] = isset($w[1]) ? $w[1] : $w[0];
            $item['label'] .= $item['name'];
            foreach($item['yearGroupList'] as $q=>$w) {
                $item['yearGroupList'][$q] = ProviderFactory::create(YearGroup::class)->findOne($w);
            }
            return $item;
        }, $result);

        usort($result, function($a, $b) {
            $retval = $a['nameShort'] <=> $b['nameShort'];
            if ($retval === 0) {
                $retval = $b['name'] <=> $a['name'];
            }
            return $retval;
        });

        $dataPoints = ProviderFactory::create(Setting::class)->getSettingByScopeAsArray('Tracking', 'externalAssessmentDataPoints');

        foreach($result as $q=>$item)
        {
            foreach($dataPoints as $point)
            {
                if ($item['id'] === intval($point['assessment']) && $item['category'] === $point['category'])
                {
                    $result[$q]['yearGroupList'] = $point['yearGroupList'];
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * findFieldSetChoices
     * @return array
     */
    public function findFieldSetChoices(): array
    {
        $choices = $this->getRepository()->findFieldSetChoices();

        $result = [];
        foreach($choices as $choice)
        {
            $cat = explode('_', $choice['category']);
            $cat = array_pop($cat);
            $w = new ChoiceView(null, $choice['category'], $cat);
            $result[intval($choice['ea_id'])][$cat]  = $w;
        }
        return $result;
    }
}