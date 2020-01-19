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

use Kookaburra\RollGroups\Entity\RollGroup;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\UserAdmin\Util\UserHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AttendanceLogRollGroup
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\AttendanceLogRollGroupRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="AttendanceLogRollGroup",
 *      indexes={
 *     @ORM\Index(name="roll_group",columns={"roll_group"}),
 *     @ORM\Index(name="taker",columns={"taker"})
 * }
 * )
 * @ORM\HasLifecycleCallbacks
 */
class AttendanceLogRollGroup
{
    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="bigint", columnDefinition="INT(14) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var RollGroup|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\RollGroups\Entity\RollGroup")
     * @ORM\JoinColumn(name="roll_group", referencedColumnName="id", nullable=false)
     */
    private $rollGroup;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="taker", referencedColumnName="id", nullable=false)
     */
    private $personTaker;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", name="timestampTaken", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $timestampTaken;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return AttendanceLogRollGroup
     */
    public function setId(?int $id): AttendanceLogRollGroup
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return RollGroup|null
     */
    public function getRollGroup(): ?RollGroup
    {
        return $this->rollGroup;
    }

    /**
     * @param RollGroup|null $rollGroup
     * @return AttendanceLogRollGroup
     */
    public function setRollGroup(?RollGroup $rollGroup): AttendanceLogRollGroup
    {
        $this->rollGroup = $rollGroup;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getPersonTaker(): ?Person
    {
        return $this->personTaker;
    }

    /**
     * @param Person|null $personTaker
     * @return AttendanceLogRollGroup
     */
    public function setPersonTaker(?Person $personTaker): AttendanceLogRollGroup
    {
        $this->personTaker = $personTaker;
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
     * @return AttendanceLogRollGroup
     */
    public function setDate(?\DateTime $date): AttendanceLogRollGroup
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTimestampTaken(): ?\DateTime
    {
        return $this->timestampTaken;
    }

    /**
     * setTimestampTaken
     * @param \DateTime|null $timestampTaken
     * @return AttendanceLogRollGroup
     * @throws \Exception
     */
    public function setTimestampTaken(?\DateTime $timestampTaken = null): AttendanceLogRollGroup
    {
        $this->timestampTaken = $timestampTaken ?: new \DateTime('now');
        return $this;
    }

    /**
     * createTakerTimeStamps
     * @return AttendanceLogRollGroup
     * @throws \Exception
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function createTakerTimeStamps(): AttendanceLogRollGroup
    {
        if (empty($this->getPersonTaker()))
            $this->setPersonTaker(UserHelper::getCurrentUser());
        return $this->setTimestampTaken(new \DateTime('now'));
    }
}