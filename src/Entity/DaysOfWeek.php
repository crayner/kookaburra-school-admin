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
use App\Manager\Traits\BooleanList;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class DaysOfWeek
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\DaysOfWeekRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="DaysOfWeek", uniqueConstraints={@ORM\UniqueConstraint(name="name",columns={"name"}),@ORM\UniqueConstraint(name="nameShort",columns={"nameShort"}), @ORM\UniqueConstraint(name="sequenceNumber",columns={"sequenceNumber"}) })
 * @UniqueEntity("name")
 * @UniqueEntity("nameShort")
 */
class DaysOfWeek implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="smallint", name="gibbonDaysOfWeekID", columnDefinition="INT(2) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(length=10,unique=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(length=4, name="nameShort",unique=true)
     */
    private $nameShort;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", name="sequenceNumber", columnDefinition="INT(2)")
     */
    private $sequenceNumber;

    /**
     * @var string
     * @ORM\Column(length=1, name="schoolDay", options={"default": "Y"})
     */
    private $schoolDay = 'Y';

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="time_immutable", name="schoolOpen", nullable=true)
     */
    private $schoolOpen;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="time_immutable", name="schoolStart", nullable=true)
     */
    private $schoolStart;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="time_immutable", name="schoolEnd", nullable=true)
     */
    private $schoolEnd;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="time_immutable", name="schoolClose", nullable=true)
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
     * @return DaysOfWeek
     */
    public function setId(?int $id): DaysOfWeek
    {
        $this->id = $id;
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
     * @param string $name
     * @return DaysOfWeek
     */
    public function setName(string $name): DaysOfWeek
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
     * @param string $nameShort
     * @return DaysOfWeek
     */
    public function setNameShort(string $nameShort): DaysOfWeek
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int
    {
        return $this->sequenceNumber;
    }

    /**
     * @param int|null $sequenceNumber
     * @return DaysOfWeek
     */
    public function setSequenceNumber(?int $sequenceNumber): DaysOfWeek
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    public function isSchoolDay(): bool
    {
        return $this->isTrueOrFalse($this->getSchoolDay());
    }
    /**
     * @return string
     */
    public function getSchoolDay(): string
    {
        return $this->schoolDay = self::checkBoolean($this->schoolDay, 'Y');
    }

    /**
     * @param string $schoolDay
     * @return DaysOfWeek
     */
    public function setSchoolDay(string $schoolDay): DaysOfWeek
    {
        $this->schoolDay = self::checkBoolean($schoolDay, 'Y');
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getSchoolOpen(): ?\DateTimeImmutable
    {
        return $this->schoolOpen;
    }

    /**
     * SchoolOpen.
     *
     * @param \DateTimeImmutable|null $schoolOpen
     * @return DaysOfWeek
     */
    public function setSchoolOpen(?\DateTimeImmutable $schoolOpen): DaysOfWeek
    {
        $this->schoolOpen = $schoolOpen;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getSchoolStart(): ?\DateTimeImmutable
    {
        return $this->schoolStart;
    }

    /**
     * SchoolStart.
     *
     * @param \DateTimeImmutable|null $schoolStart
     * @return DaysOfWeek
     */
    public function setSchoolStart(?\DateTimeImmutable $schoolStart): DaysOfWeek
    {
        $this->schoolStart = $schoolStart;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getSchoolEnd(): ?\DateTimeImmutable
    {
        return $this->schoolEnd;
    }

    /**
     * SchoolEnd.
     *
     * @param \DateTimeImmutable|null $schoolEnd
     * @return DaysOfWeek
     */
    public function setSchoolEnd(?\DateTimeImmutable $schoolEnd): DaysOfWeek
    {
        $this->schoolEnd = $schoolEnd;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getSchoolClose(): ?\DateTimeImmutable
    {
        return $this->schoolClose;
    }

    /**
     * SchoolClose.
     *
     * @param \DateTimeImmutable|null $schoolClose
     * @return DaysOfWeek
     */
    public function setSchoolClose(?\DateTimeImmutable $schoolClose): DaysOfWeek
    {
        $this->schoolClose = $schoolClose;
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