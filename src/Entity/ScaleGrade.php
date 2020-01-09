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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ScaleGrade
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\ScaleGradeRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="ScaleGrade",
 *     indexes={@ORM\Index(name="scale",columns={"scale"})},
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="scaleValue", columns={"scale","value"}),
*           @ORM\UniqueConstraint(name="scaleSequence", columns={"scale","sequenceNumber"})})
 * @UniqueEntity({"value","scale"})
 * @UniqueEntity({"sequenceNumber","scale"})
 */
class ScaleGrade implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="integer", columnDefinition="INT(7) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Scale|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\Scale", inversedBy="scaleGrades")
     * @ORM\JoinColumn(name="scale", referencedColumnName="id", nullable=false)
     */
    private $scale;

    /**
     * @var string|null
     * @ORM\Column(length=10)
     * @Assert\NotBlank()
     */
    private $value;

    /**
     * @var string|null
     * @ORM\Column(length=50)
     */
    private $descriptor;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", name="sequenceNumber", columnDefinition="INT(5)")
     * @Assert\Range(min=0, max=99999)
     */
    private $sequenceNumber;

    /**
     * @var string|null
     * @ORM\Column(length=1, name="isDefault", options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $isDefault = 'N';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ScaleGrade
     */
    public function setId(?int $id): ScaleGrade
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Scale|null
     */
    public function getScale(): ?Scale
    {
        return $this->scale;
    }

    /**
     * @param Scale|null $scale
     * @return ScaleGrade
     */
    public function setScale(?Scale $scale): ScaleGrade
    {
        $this->scale = $scale;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return ScaleGrade
     */
    public function setValue(?string $value): ScaleGrade
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescriptor(): ?string
    {
        return $this->descriptor;
    }

    /**
     * @param string|null $descriptor
     * @return ScaleGrade
     */
    public function setDescriptor(?string $descriptor): ScaleGrade
    {
        $this->descriptor = $descriptor;
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
     * @return ScaleGrade
     */
    public function setSequenceNumber(?int $sequenceNumber): ScaleGrade
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    /**
     * @return string|null
     */
    public function isIsDefault(): ?string
    {
        return $this->getIsDefault() === 'Y' ? true : false;
    }

    /**
     * @return string|null
     */
    public function getIsDefault(): ?string
    {
        return $this->isDefault = self::checkBoolean($this->isDefault, 'N');
    }

    /**
     * @param string|null $isDefault
     * @return ScaleGrade
     */
    public function setIsDefault(?string $isDefault): ScaleGrade
    {
        $this->isDefault = self::checkBoolean($isDefault, 'N');
        return $this;
    }

    /**
     * __toString
     * @return string
     */
    public function __toString(): string
    {
        return $this->getScale()->__toString() . ': ' . $this->getValue();
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