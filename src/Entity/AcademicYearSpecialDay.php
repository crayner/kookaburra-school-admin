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
use Kookaburra\SchoolAdmin\Validator as Check;
use Kookaburra\SchoolAdmin\Util\AcademicYearHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AcademicYearSpecialDay
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\AcademicYearSpecialDayRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="AcademicYearSpecialDay", uniqueConstraints={@ORM\UniqueConstraint(name="date", columns={"date"})})
 * @Check\SpecialDay()
 */
class AcademicYearSpecialDay implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id", columnDefinition="INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var AcademicYearTerm|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\AcademicYearTerm")
     * @ORM\JoinColumn(name="gibbonAcademicYearTermID", referencedColumnName="gibbonAcademicYearTermID", nullable=false)
     */
    private $AcademicYearTerm;

    /**
     * @var string
     * @ORM\Column(length=14, name="type")
     */
    private $type ;

    /**
     * @var array
     */
    private static $typeList = ['School Closure', 'Timing Change'];

    /**
     * @var string|null
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column()
     */
    private $description;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date", unique=true)
     * @Assert\NotBlank()
     */
    private $date;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", name="schoolOpen", nullable=true)
     */
    private $schoolOpen;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", name="schoolStart", nullable=true)
     */
    private $schoolStart;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", name="schoolEnd", nullable=true)
     */
    private $schoolEnd;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="time", name="schoolClose", nullable=true)
     */
    private $schoolClose;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return AcademicYearSpecialDay
     */
    public function setId(?int $id): AcademicYearSpecialDay
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return AcademicYearTerm|null
     */
    public function getAcademicYearTerm(): ?AcademicYearTerm
    {
        return $this->AcademicYearTerm;
    }

    /**
     * @param AcademicYearTerm|null $AcademicYearTerm
     * @return AcademicYearSpecialDay
     */
    public function setAcademicYearTerm(?AcademicYearTerm $AcademicYearTerm): AcademicYearSpecialDay
    {
        $this->AcademicYearTerm = $AcademicYearTerm;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return AcademicYearSpecialDay
     */
    public function setType(string $type): AcademicYearSpecialDay
    {
        $this->type = $type;
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
     * @return AcademicYearSpecialDay
     */
    public function setName(?string $name): AcademicYearSpecialDay
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return AcademicYearSpecialDay
     */
    public function setDescription(?string $description): AcademicYearSpecialDay
    {
        $this->description = $description;
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
     * @return AcademicYearSpecialDay
     */
    public function setDate(?\DateTime $date): AcademicYearSpecialDay
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getSchoolOpen(): ?\DateTime
    {
        return $this->schoolOpen;
    }

    /**
     * @param \DateTime|null $schoolOpen
     * @return AcademicYearSpecialDay
     */
    public function setSchoolOpen(?\DateTime $schoolOpen): AcademicYearSpecialDay
    {
        $this->schoolOpen = $schoolOpen;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getSchoolStart(): ?\DateTime
    {
        return $this->schoolStart;
    }

    /**
     * @param \DateTime|null $schoolStart
     * @return AcademicYearSpecialDay
     */
    public function setSchoolStart(?\DateTime $schoolStart): AcademicYearSpecialDay
    {
        $this->schoolStart = $schoolStart;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getSchoolEnd(): ?\DateTime
    {
        return $this->schoolEnd;
    }

    /**
     * @param \DateTime|null $schoolEnd
     * @return AcademicYearSpecialDay
     */
    public function setSchoolEnd(?\DateTime $schoolEnd): AcademicYearSpecialDay
    {
        $this->schoolEnd = $schoolEnd;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getSchoolClose(): ?\DateTime
    {
        return $this->schoolClose;
    }

    /**
     * @param \DateTime|null $schoolClose
     * @return AcademicYearSpecialDay
     */
    public function setSchoolClose(?\DateTime $schoolClose): AcademicYearSpecialDay
    {
        $this->schoolClose = $schoolClose;
        return $this;
    }

    /**
     * @param \DateTime $date
     * @return AcademicYearSpecialDay
     */
    public static function createSpecialDay(\DateTime $date): AcademicYearSpecialDay
    {
        $self = new self();
        $self->setDate($date);
        $self->setType('School Closure');
        $self->setName('ERROR');
        $self->setDescription('Database Error: The date was not found in the term data.');
        $self->setAcademicYearTerm(AcademicYearHelper::findOneTermByDay($date));
        return $self;
    }

    /**
     * getTypeList
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
    }

    /**
     * __toString
     * @return string
     */
    public function __toString(): string
    {
        return $this->getDate()->format('Y-m-d');
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