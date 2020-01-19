<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 20 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/20
 * Time: 15:27
 */
namespace Kookaburra\SchoolAdmin\Entity;

use App\Manager\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class IndividualNeeds
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\IndividualNeedsRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="IN",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="person", columns={"person"})})
 * @UniqueEntity({"person"})
 */
class IndividualNeeds implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", columnDefinition="INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT")
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
     * @var string|null
     * @ORM\Column(type="text")
     */
    private $strategies;

    /**
     * @var string|null
     * @ORM\Column(type="text")
     */
    private $targets;

    /**
     * @var string|null
     * @ORM\Column(type="text")
     */
    private $notes;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return In
     */
    public function setId(?int $id): IndividualNeeds
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
     * @return In
     */
    public function setPerson(?Person $person): IndividualNeeds
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStrategies(): ?string
    {
        return $this->strategies;
    }

    /**
     * @param string|null $strategies
     * @return In
     */
    public function setStrategies(?string $strategies): IndividualNeeds
    {
        $this->strategies = $strategies;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTargets(): ?string
    {
        return $this->targets;
    }

    /**
     * @param string|null $targets
     * @return In
     */
    public function setTargets(?string $targets): IndividualNeeds
    {
        $this->targets = $targets;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param string|null $notes
     * @return In
     */
    public function setNotes(?string $notes): IndividualNeeds
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * toArray
     * @param string|null $name
     * @return array
     */
    public function toArray(?string $name = null): array
    {
        return [
            'name' => $this->getN
        ];
    }
}