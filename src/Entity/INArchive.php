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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class INArchive
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\INArchiveRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="INArchive")
 */
class INArchive
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
     * @var INDescriptor[]|Collection
     * @ORM\ManyToMany(targetEntity="INDescriptor")
     * @ORM\JoinTable(name="INArchiveDescriptors",
     *      joinColumns={@ORM\JoinColumn(name="in_archive", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="in_descriptor", referencedColumnName="id")}
     *      )
     */
    private $descriptors;

    /**
     * @var string|null
     * @ORM\Column(length=50, name="archiveTitle")
     */
    private $archiveTitle;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", name="archiveTimestamp", nullable=true)
     */
    private $archiveTimestamp;

    /**
     * INArchive constructor.
     */
    public function __construct()
    {
        $this->descriptors = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return INArchive
     */
    public function setId(?int $id): INArchive
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
     * @return INArchive
     */
    public function setPerson(?Person $person): INArchive
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
     * @return INArchive
     */
    public function setStrategies(?string $strategies): INArchive
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
     * @return INArchive
     */
    public function setTargets(?string $targets): INArchive
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
     * @return INArchive
     */
    public function setNotes(?string $notes): INArchive
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return Collection|INDescriptor[]
     */
    public function getDescriptors(): Collection
    {
        if (null === $this->descriptors)
            $this->descriptors = new ArrayCollection();
        if ($this->descriptors instanceof PersistentCollection)
            $this->descriptors->initialize();

        return $this->descriptors;
    }

    /**
     * Descriptors.
     *
     * @param Collection|INDescriptor[] $descriptors
     * @return INArchive
     */
    public function setDescriptors($descriptors)
    {
        $this->descriptors = $descriptors;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getArchiveTitle(): ?string
    {
        return $this->archiveTitle;
    }

    /**
     * @param string|null $archiveTitle
     * @return INArchive
     */
    public function setArchiveTitle(?string $archiveTitle): INArchive
    {
        $this->archiveTitle = $archiveTitle;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getArchiveTimestamp(): ?\DateTime
    {
        return $this->archiveTimestamp;
    }

    /**
     * @param \DateTime|null $archiveTimestamp
     * @return INArchive
     */
    public function setArchiveTimestamp(?\DateTime $archiveTimestamp): INArchive
    {
        $this->archiveTimestamp = $archiveTimestamp;
        return $this;
    }
}