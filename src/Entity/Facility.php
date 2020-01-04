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
use App\Provider\ProviderFactory;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Facility
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\FacilityRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="Facility", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name"})})
 */
class Facility implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", columnDefinition="INT(10) UNSIGNED ZEROFILL")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=30, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=50)
     * @Assert\Choice(callback="getTypeList")
     */
    private $type;

    /**
     * @var integer
     * @ORM\Column(type="integer",columnDefinition="INT(5)",nullable=true)
     * @Assert\Range(min=0,max=99999)
     */
    private $capacity;

    /**
     * @var string
     * @ORM\Column(length=1, options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $computer = 'N';

    /**
     * @var integer
     * @ORM\Column(type="integer", columnDefinition="INT(3)", name="computerStudent", options={"default": "0"})
     */
    private $studentComputers = 0;

    /**
     * @var string
     * @ORM\Column(length=1, options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $projector = 'N';

    /**
     * @var string
     * @ORM\Column(length=1, options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $tv = 'N';

    /**
     * @var boolean
     * @ORM\Column(length=1, options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $dvd = 'N';

    /**
     * @var string
     * @ORM\Column(length=1, options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $hifi = 'N';

    /**
     * @var string
     * @ORM\Column(length=1, options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $speakers = "N'";

    /**
     * @var string
     * @ORM\Column(length=1, options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $iwb = 'N';

    /**
     * @var string|null
     * @ORM\Column(length=5, name="phoneInternal")
     */
    private $phoneInt = '';

    /**
     * @var string|null
     * @ORM\Column(length=20, name="phoneExternal")
     */
    private $phoneExt = '';

    /**
     * @var string|null
     * @ORM\Column(type="text")
     */
    private $comment = '';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Facility
     */
    public function setId(?int $id): Facility
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
     * @param string|null $name
     * @return Facility
     */
    public function setName(?string $name): Facility
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Facility
     */
    public function setType(?string $type): Facility
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     * @return Facility
     */
    public function setCapacity(int $capacity): Facility
    {
        $this->capacity = $capacity;
        return $this;
    }

    /**
     * getComputer
     * @return string
     */
    public function getComputer(): string
    {
        return $this->computer = self::checkBoolean($this->computer, 'N');
    }

    /**
     * setComputer
     * @param string|null $computer
     * @return Facility
     */
    public function setComputer(?string $computer): Facility
    {
        $this->computer = self::checkBoolean($computer, 'N');
        return $this;
    }

    /**
     * @return int
     */
    public function getStudentComputers(): int
    {
        return $this->studentComputers;
    }

    /**
     * @param int $studentComputers
     * @return Facility
     */
    public function setStudentComputers(int $studentComputers): Facility
    {
        $this->studentComputers = $studentComputers;
        return $this;
    }

    /**
     * getProjector
     * @return string
     */
    public function getProjector(): string
    {
        return $this->projector = self::checkBoolean($this->projector, 'N');
    }

    /**
     * setProjector
     * @param string|null $projector
     * @return Facility
     */
    public function setProjector(?string $projector): Facility
    {
        $this->projector = self::checkBoolean($projector, 'N');
        return $this;
    }

    /**
     * getTv
     * @return bool
     */
    public function getTv(): string
    {
        return $this->tv = self::checkBoolean($this->tv, 'N');
    }

    /**
     * setTv
     * @param string|null $tv
     * @return Facility
     */
    public function setTv(?string $tv): Facility
    {
        $this->tv = self::checkBoolean($tv, 'N');
        return $this;
    }

    /**
     * getDvd
     * @return string
     */
    public function getDvd(): string
    {
        return $this->dvd = self::checkBoolean($this->dvd, 'N');
    }

    /**
     * setDvd
     * @param string|null $dvd
     * @return Facility
     */
    public function setDvd(?string $dvd): Facility
    {
        $this->dvd = self::checkBoolean($dvd, 'N');
        return $this;
    }

    /**
     * getHifi
     * @return string
     */
    public function getHifi(): string
    {
        return $this->hifi = self::checkBoolean($this->hifi, 'N');
    }

    /**
     * setHifi
     * @param string|null $hifi
     * @return Facility
     */
    public function setHifi(?string $hifi): Facility
    {
        $this->hifi = self::checkBoolean($hifi, 'N');
        return $this;
    }

    /**
     * getSpeakers
     * @return string
     */
    public function getSpeakers(): string
    {
        return $this->speakers = self::checkBoolean($this->speakers, 'N');
    }

    /**
     * setSpeakers
     * @param string|null $speakers
     * @return Facility
     */
    public function setSpeakers(?string $speakers): Facility
    {
        $this->speakers = self::checkBoolean($speakers, 'N');
        return $this;
    }

    /**
     * getIwb
     * @return string
     */
    public function getIwb(): string
    {
        return $this->iwb = self::checkBoolean($this->iwb, 'N');
    }

    /**
     * setIwb
     * @param string|null $iwb
     * @return Facility
     */
    public function setIwb(?string $iwb): Facility
    {
        $this->iwb = self::checkBoolean($iwb, 'N');
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneInt(): ?string
    {
        return $this->phoneInt;
    }

    /**
     * @param string|null $phoneInt
     * @return Facility
     */
    public function setPhoneInt(?string $phoneInt): Facility
    {
        $this->phoneInt = $phoneInt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneExt(): ?string
    {
        return $this->phoneExt;
    }

    /**
     * @param string|null $phoneExt
     * @return Facility
     */
    public function setPhoneExt(?string $phoneExt): Facility
    {
        $this->phoneExt = $phoneExt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     * @return Facility
     */
    public function setComment(?string $comment): Facility
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * getTypeList
     * @return array
     */
    public static function getTypeList(): array
    {
        return ProviderFactory::create(Setting::class)->getSettingByScopeAsArray('School Admin', 'facilityTypes');
    }

    /**
     * __toString
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName().' ('.$this->getCapacity().')';
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