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

use Doctrine\Common\Collections\ArrayCollection;

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
}