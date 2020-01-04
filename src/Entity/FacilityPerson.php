<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace Kookaburra\SchoolAdmin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class FacilityPerson
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\FacilityPersonRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="FacilityPerson",
 *     indexes={@ORM\Index(name="facility",columns={"facility"})})
 */
class FacilityPerson
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint",columnDefinition="INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Facility|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\Facility")
     * @ORM\JoinColumn(name="facility", referencedColumnName="id", nullable=false)
     */
    private $facility;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonID", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $person;

    /**
     * @var string|null
     * @ORM\Column(length=8, name="usageType", nullable=true)
     */
    private $usageType;

    /**
     * @var array
     */
    private static $usageTypeList = ['', 'Teaching', 'Office', 'Other'];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return FacilityPerson
     */
    public function setId(?int $id): FacilityPerson
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Facility|null
     */
    public function getFacility(): ?Facility
    {
        return $this->facility;
    }

    /**
     * Facility.
     *
     * @param Facility|null $facility
     * @return FacilityPerson
     */
    public function setFacility(?Facility $facility): FacilityPerson
    {
        $this->facility = $facility;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return FacilityPerson
     */
    public function setPerson(?Person $person): FacilityPerson
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsageType(): ?string
    {
        return $this->usageType;
    }

    /**
     * @param string|null $usageType
     * @return FacilityPerson
     */
    public function setUsageType(?string $usageType): FacilityPerson
    {
        $this->usageType = in_array($usageType, self::getUsageTypeList()) ? $usageType : null;
        return $this;
    }

    /**
     * @return array
     */
    public static function getUsageTypeList(): array
    {
        return self::$usageTypeList;
    }
}