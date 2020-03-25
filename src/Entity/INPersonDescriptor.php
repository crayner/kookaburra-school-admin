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

use App\Manager\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class INPersonDescriptor
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\INPersonDescriptorRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="INPersonDescriptor")
 */
class INPersonDescriptor implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint", columnDefinition="INT(12) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="person", referencedColumnName="id", nullable=false)
     */
    private $person;

    /**
     * @var INDescriptor|null
     * @ORM\ManyToOne(targetEntity="INDescriptor")
     * @ORM\JoinColumn(name="in_descriptor", referencedColumnName="id", nullable=false)
     */
    private $inDescriptor;

    /**
     * @var AlertLevel|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\AlertLevel")
     * @ORM\JoinColumn(name="alert_level", referencedColumnName="id", nullable=false)
     */
    private $alertLevel;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return INPersonDescriptor
     */
    public function setId(?int $id): INPersonDescriptor
    {
        $this->id = $id;
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
     * @return INPersonDescriptor
     */
    public function setPerson(?Person $person): INPersonDescriptor
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return INDescriptor|null
     */
    public function getInDescriptor(): ?INDescriptor
    {
        return $this->inDescriptor;
    }

    /**
     * @param INDescriptor|null $inDescriptor
     * @return INPersonDescriptor
     */
    public function setInDescriptor(?INDescriptor $inDescriptor): INPersonDescriptor
    {
        $this->inDescriptor = $inDescriptor;
        return $this;
    }

    /**
     * @return AlertLevel|null
     */
    public function getAlertLevel(): ?AlertLevel
    {
        return $this->alertLevel;
    }

    /**
     * @param AlertLevel|null $alertLevel
     * @return INPersonDescriptor
     */
    public function setAlertLevel(?AlertLevel $alertLevel): INPersonDescriptor
    {
        $this->alertLevel = $alertLevel;
        return $this;
    }

    /**
     * toArray
     * @param string|null $name
     * @return array
     */
    public function toArray(?string $name = null): array
    {
        return [];
    }
}