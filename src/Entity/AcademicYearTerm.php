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

/**
 * Class AcademicYearTerm
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\AcademicYearTermRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="AcademicYearTerm", uniqueConstraints={@ORM\UniqueConstraint(name="sequenceNumber", columns={"sequenceNumber","id"})})
 */
class AcademicYearTerm implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="id", columnDefinition="INT(5) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var AcademicYear|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\AcademicYear")
     * @ORM\JoinColumn(name="id", referencedColumnName="id", nullable=false)
     */
    private $AcademicYear;

    /**
     * @var integer
     * @ORM\Column(type="smallint",columnDefinition="INT(5)",name="sequenceNumber")
     */
    private $sequenceNumber;

    /**
     * @var string|null
     * @ORM\Column(length=20)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=4, name="nameShort")
     */
    private $nameShort;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date", name="firstDay")
     */
    private $firstDay;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date", name="lastDay")
     */
    private $lastDay;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return AcademicYearTerm
     */
    public function setId(?int $id): AcademicYearTerm
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return AcademicYear|null
     */
    public function getAcademicYear(): ?AcademicYear
    {
        return $this->AcademicYear;
    }

    /**
     * @param AcademicYear|null $AcademicYear
     * @return AcademicYearTerm
     */
    public function setAcademicYear(?AcademicYear $AcademicYear): AcademicYearTerm
    {
        $this->AcademicYear = $AcademicYear;
        return $this;
    }

    /**
     * @return int
     */
    public function getSequenceNumber(): int
    {
        return $this->sequenceNumber;
    }

    /**
     * @param int $sequenceNumber
     * @return AcademicYearTerm
     */
    public function setSequenceNumber(int $sequenceNumber): AcademicYearTerm
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return AcademicYearTerm
     */
    public function setName(?string $name): AcademicYearTerm
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNameShort(): ?string
    {
        return $this->nameShort;
    }

    /**
     * @param string|null $nameShort
     * @return AcademicYearTerm
     */
    public function setNameShort(?string $nameShort): AcademicYearTerm
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFirstDay(): ?\DateTime
    {
        return $this->firstDay;
    }

    /**
     * @param \DateTime|null $firstDay
     * @return AcademicYearTerm
     */
    public function setFirstDay(?\DateTime $firstDay): AcademicYearTerm
    {
        $this->firstDay = $firstDay;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastDay(): ?\DateTime
    {
        return $this->lastDay;
    }

    /**
     * @param \DateTime|null $lastDay
     * @return AcademicYearTerm
     */
    public function setLastDay(?\DateTime $lastDay): AcademicYearTerm
    {
        $this->lastDay = $lastDay;
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