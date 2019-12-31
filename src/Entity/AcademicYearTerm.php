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
use Kookaburra\SchoolAdmin\Validator as Check;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class AcademicYearTerm
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\AcademicYearTermRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="AcademicYearTerm", uniqueConstraints={@ORM\UniqueConstraint(name="sequenceNumber", columns={"academic_year","sequenceNumber"}), @ORM\UniqueConstraint(name="abbr", columns={"academic_year","nameShort"}), @ORM\UniqueConstraint(name="name", columns={"academic_year","name"})})
 * @UniqueEntity({"academicYear","sequenceNumber"})
 * @UniqueEntity({"academicYear","name"})
 * @UniqueEntity({"academicYear","snameShort"})
 * @Check\Term()
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
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\AcademicYear", inversedBy="terms")
     * @ORM\JoinColumn(name="academic_year",referencedColumnName="id",nullable=false)
     * @Assert\NotBlank()
     */
    private $academicYear;

    /**
     * @var integer
     * @ORM\Column(type="smallint",columnDefinition="INT(5)",name="sequenceNumber",unique=true)
     */
    private $sequenceNumber;

    /**
     * @var string|null
     * @ORM\Column(length=20)
     * @Assert\Length(max=20)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=4, name="nameShort")
     * @Assert\Length(max=4)
     * @Assert\NotBlank()
     */
    private $nameShort;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="date_immutable",name="firstDay",nullable=true)
     * @Assert\NotBlank()
     */
    private $firstDay;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="date_immutable",name="lastDay",nullable=true)
     * @Assert\NotBlank()
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
        return $this->academicYear;
    }

    /**
     * @param AcademicYear|null $academicYear
     * @return AcademicYearTerm
     */
    public function setAcademicYear(?AcademicYear $academicYear): AcademicYearTerm
    {
        $this->academicYear = $academicYear;
        return $this;
    }

    /**
     * @return int
     */
    public function getSequenceNumber(): int
    {
        return intval($this->sequenceNumber);
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
     * @return \DateTimeImmutable|null
     */
    public function getFirstDay(): ?\DateTimeImmutable
    {
        return $this->firstDay;
    }

    /**
     * FirstDay.
     *
     * @param \DateTimeImmutable|null $firstDay
     * @return AcademicYearTerm
     */
    public function setFirstDay(?\DateTimeImmutable $firstDay): AcademicYearTerm
    {
        $this->firstDay = $firstDay;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getLastDay(): ?\DateTimeImmutable
    {
        return $this->lastDay;
    }

    /**
     * LastDay.
     *
     * @param \DateTimeImmutable|null $lastDay
     * @return AcademicYearTerm
     */
    public function setLastDay(?\DateTimeImmutable $lastDay): AcademicYearTerm
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
        $dates = $this->getFirstDay()->format('d M Y') . ' - ' . $this->getLastDay()->format('d M Y');
        return [
            'name' => $this->getName(),
            'abbr' => $this->getNameShort(),
            'year' => $this->getAcademicYear()->getName(),
            'dates' => $dates,
            'canDelete' => true,
            'sequence' => $this->getSequenceNumber(),
        ];
    }
}