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

use App\Validator AS Validator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AlertLevel
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\AlertLevelRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="AlertLevel")
 */
class AlertLevel
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="smallint", columnDefinition="INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=50)
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=4, name="nameShort")
     * @Assert\NotBlank()
     * @Assert\Length(max=4)
     */
    private $nameShort;

    /**
     * @var string|null
     * @ORM\Column(length=6, name="color", options={"comment": "RGB Hex, no leading #"})
     * @Validator\Colour(enforceType="hex")
     */
    private $colour;

    /**
     * @var string|null
     * @ORM\Column(length=6, name="colorBG", options={"comment": "RGB Hex, no leading #"})
     * @Validator\Colour(enforceType="hex")
     */
    private $colourBG;

    /**
     * @var string|null
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var int
     * @ORM\Column(type="smallint",columnDefinition="INT(3)",name="sequenceNumber")
     * @Assert\Range(min=1,max=999)
     */
    private $sequenceNumber;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return AlertLevel
     */
    public function setId(?int $id): AlertLevel
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
     * @return AlertLevel
     */
    public function setName(?string $name): AlertLevel
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
     * @return AlertLevel
     */
    public function setNameShort(?string $nameShort): AlertLevel
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getColour(): ?string
    {
        return $this->colour;
    }

    /**
     * @param string|null $colour
     * @return AlertLevel
     */
    public function setColour(?string $colour): AlertLevel
    {
        $this->colour = $colour;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getColourBG(): ?string
    {
        return $this->colourBG;
    }

    /**
     * @param string|null $colourBG
     * @return AlertLevel
     */
    public function setColourBG(?string $colourBG): AlertLevel
    {
        $this->colourBG = $colourBG;
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
     * @return AlertLevel
     */
    public function setDescription(?string $description): AlertLevel
    {
        $this->description = $description;
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
     * @return AlertLevel
     */
    public function setSequenceNumber(int $sequenceNumber): AlertLevel
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }
}