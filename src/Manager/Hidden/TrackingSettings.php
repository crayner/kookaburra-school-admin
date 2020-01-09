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

use App\Entity\Setting;
use App\Provider\ProviderFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TrackingSettings
 * @package Kookaburra\SchoolAdmin\Manager\Hidden
 */
class TrackingSettings
{
    /**
     * @var ArrayCollection
     */
    private $external;
    /**
     * @var ArrayCollection
     */
    private $internal;

    /**
     * @return ArrayCollection
     */
    public function getExternal(): ArrayCollection
    {
        if (null === $this->external)
            $this->external = new ArrayCollection();
        return $this->external;
    }

    /**
     * External.
     *
     * @param ArrayCollection $external
     * @return TrackingSettings
     */
    public function setExternal(ArrayCollection $external): TrackingSettings
    {
        $this->external = $external;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getInternal(): ArrayCollection
    {
        if (null === $this->internal)
            $this->internal = new ArrayCollection();
        return $this->internal;
    }

    /**
     * Internal.
     *
     * @param ArrayCollection $internal
     * @return TrackingSettings
     */
    public function setInternal(ArrayCollection $internal): TrackingSettings
    {
        $this->internal = $internal;
        return $this;
    }

    /**
     * convertInternal
     * @param array $data
     * @return ArrayCollection
     */
    public static function convertInternal(array $data): ArrayCollection
    {
        foreach($data['names'] as $name)
        {
            $found = false;
            foreach($data['tracking'] as $point)
            {
                if ($name === $point['type'])
                {
                    $found = true;
                    break;
                }
            }
            if (!$found)
            {
                $track = [];
                $track['type'] = $name;
                $track['yearGroupList'] = [];
                $data['track'][] = $track;
            }
        }

        foreach($data['tracking'] as $q=>$point)
        {
            $found = false;
            foreach($data['names'] as $name)
            {
                if ($name === $point['type'])
                {
                    $found = true;
                    break;
                }
            }
            if (!$found)
            {
                unset($data['tracking'][$q]);
            }
        }

        return new ArrayCollection($data['tracking']);
    }

    /**
     * manageExternal
     * @param array $external
     */
    public function manageExternal(array $external)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'category',
                'externalAssessment',
            ]
        );
        $resolver->setDefaults(
            [
                'yearGroupList' => [],
            ]
        );
        $resolver->setAllowedTypes('category', 'string')->setAllowedTypes('externalAssessment', 'integer');
        foreach($external as $q=>$item)
        {
            try {
                if (isset($item['externalAssessment']))
                    $item['externalAssessment'] = intval($item['externalAssessment']);
                $external[$q] = $resolver->resolve($item);
            } catch (MissingOptionsException $e) {
                unset($external[$q]);
            }
        }
        ProviderFactory::create(Setting::class)->setSettingByScope('Tracking','externalAssessmentDataPoints',serialize($external));
    }

    /**
     * manageInternal
     * @param array $internal
     */
    public function manageInternal(array $internal)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'type',
            ]
        );
        $resolver->setDefaults(
            [
                'yearGroupList' => [],
            ]
        );
        $resolver->setAllowedTypes('type', 'string');
        foreach($internal as $q=>$item)
        {
            try {
                $internal[$q] = $resolver->resolve($item);
            } catch (MissingOptionsException $e) {
                unset($internal[$q]);
            }
        }

        ProviderFactory::create(Setting::class)->setSettingByScope('Tracking','internalAssessmentDataPoints',serialize($internal));
    }
}