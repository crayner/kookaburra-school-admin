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
 * Class ExternalAssessmentStudent
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\ExternalAssessmentStudentRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="ExternalAssessmentStudent",
 *     indexes={@ORM\Index(name="external_assessment", columns={"external_assessment"}),
 *     @ORM\Index(name="person", columns={"person"})})
 */
class ExternalAssessmentStudent
{
    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="bigint", columnDefinition="INT(12) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var ExternalAssessment|null
     * @ORM\ManyToOne(targetEntity="ExternalAssessment")
     * @ORM\JoinColumn(name="external_assessment", referencedColumnName="id", nullable=false)
     */
    private $externalAssessment;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="person", referencedColumnName="gibbonPersonID", nullable=false)
     */
    private $person;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @var string|null
     * @ORM\Column()
     */
    private $attachment;

    /**
     * @var ExternalAssessmentStudentEntry|null
     * @ORM\OneToMany(targetEntity="Kookaburra\SchoolAdmin\Entity\ExternalAssessmentStudentEntry", mappedBy="externalAssessmentStudent")
     */
    private $entries;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ExternalAssessmentStudent
     */
    public function setId(?int $id): ExternalAssessmentStudent
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return ExternalAssessment|null
     */
    public function getExternalAssessment(): ?ExternalAssessment
    {
        return $this->externalAssessment;
    }

    /**
     * @param ExternalAssessment|null $externalAssessment
     * @return ExternalAssessmentStudent
     */
    public function setExternalAssessment(?ExternalAssessment $externalAssessment): ExternalAssessmentStudent
    {
        $this->externalAssessment = $externalAssessment;
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
     * @return ExternalAssessmentStudent
     */
    public function setPerson(?Person $person): ExternalAssessmentStudent
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     * @return ExternalAssessmentStudent
     */
    public function setDate(?\DateTime $date): ExternalAssessmentStudent
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    /**
     * @param string|null $attachment
     * @return ExternalAssessmentStudent
     */
    public function setAttachment(?string $attachment): ExternalAssessmentStudent
    {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * @return ExternalAssessmentStudentEntry|null
     */
    public function getEntries(): ?ExternalAssessmentStudentEntry
    {
        return $this->entries;
    }

    /**
     * Entries.
     *
     * @param ExternalAssessmentStudentEntry|null $entries
     * @return ExternalAssessmentStudent
     */
    public function setEntries(?ExternalAssessmentStudentEntry $entries): ExternalAssessmentStudent
    {
        $this->entries = $entries;
        return $this;
    }
}