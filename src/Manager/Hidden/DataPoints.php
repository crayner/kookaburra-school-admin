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
 * Date: 5/01/2020
 * Time: 15:46
 */

namespace Kookaburra\SchoolAdmin\Manager\Hidden;

use Kookaburra\SystemAdmin\Entity\Setting;
use App\Provider\ProviderFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TrackingSettings
 * @package Kookaburra\SchoolAdmin\Manager\Hidden
 */
class DataPoints
{
    /**
     * @var ArrayCollection
     */
    private $dataPoints;

    /**
     * @return ArrayCollection
     */
    public function getDataPoints(): ArrayCollection
    {
        return $this->dataPoints;
    }

    /**
     * DataPoints.
     *
     * @param ArrayCollection $dataPoints
     * @return DataPoints
     */
    public function setDataPoints(ArrayCollection $dataPoints): DataPoints
    {
        $this->dataPoints = $dataPoints;
        return $this;
    }

    /**
     * convertExternal
     * @param array $data
     * @return DataPoints
     */
    public function convertExternal(array $data): self
    {
        foreach($data as $q=>$w)
        {
            $data[$q]['assessment'] = $w['id'];
            $ygl = is_array($w['yearGroupList']) ? $w['yearGroupList'] : [];
            foreach($ygl as $e=>$x)
                $data[$q]['yearGroupList'][$e] = ProviderFactory::create(YearGroup::class)->findOne($x) ;
        }

        return $this->setDataPoints(new ArrayCollection($data));
    }

    /**
     * convertInternal
     * @param array $data
     * @return DataPoints
     */
    public function convertInternal(array $data): self
    {
        $result = [];
        foreach($data['names'] as $name)
        {
            $typeExists = false;
            foreach($data['tracking'] as $q=>$w) {
                if ($name === $w['type']) {
                    $typeExists = true;
                    break;
                }
            }
            if (!$typeExists) {
                $type = [];
                $type['type'] = $name;
                $type['yearGroupList'] = [];
                $data['tracking'][] = $type;
            }
        }

        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'id',
                'category',
                'label',
            ]
        );
        $resolver->setDefaults(
            [
                'assessment'  => '',
                'yearGroupList' => [],
            ]
        );
        foreach($data['tracking'] as $q=>$w) {
            $t = [];
            $t['id'] = $q;
            $t['label'] = $t['category'] = $w['type'];
            $ygl = is_array($w['yearGroupList']) ? $w['yearGroupList'] : [];
            foreach($ygl as $x)
                $t['yearGroupList'][] = ProviderFactory::create(YearGroup::class)->findOne($x) ;
            $result[] = $resolver->resolve($t);
        }

        return $this->setDataPoints(new ArrayCollection($result));
    }

    /**
     * handleInternalRequest
     * @param array $content
     * @return DataPoints
     */
    public function handleInternalRequest(array $content): self
    {
        $dataPoints = $content['dataPoints'];

        $data = [];
        $names = [];
        foreach($dataPoints as $q=>$w)
        {
            $item = [];
            $item['type'] = $w['category'];
            $names[] = $w['category'];
            $item['yearGroupList'] = [];
            $ygl = is_array($w['yearGroupList']) ? $w['yearGroupList'] : [];
            foreach($ygl as $value)
                $item['yearGroupList'][] = intval($value);
            $data[] = $item;
        }

        ProviderFactory::create(Setting::class)->setSettingByScope('Tracking', 'internalAssessmentDataPoints', serialize($data));

        $result = [];
        $result['names'] = $names;
        $result['tracking'] = $data;
        $this->convertInternal($result);

        return $this;
    }

    /**
     * handleExternalRequest
     * @param array $content
     * @return $this
     */
    public function handleExternalRequest(array $content): self
    {
        $data = [];
        foreach($content['dataPoints'] as $w) {
            $item = [];
            $item['assessment'] = $w['assessment'];
            $item['category'] = $w['category'];
            $item['yearGroupList'] = $w['yearGroupList'];
            $data[] = $item;
        }

        ProviderFactory::create(Setting::class)->setSettingByScope('Tracking', 'externalAssessmentDataPoints', serialize($data));

        $fields = ProviderFactory::create(ExternalAssessmentField::class)->findByActiveInEAOrder();

        $this->convertExternal($fields);
        return $this;
    }
}