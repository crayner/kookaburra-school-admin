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
use App\Provider\ProviderFactory;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class INDescriptor
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\INDescriptorRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="INDescriptor",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="name",columns={"name"}),
 *      @ORM\UniqueConstraint(name="abbr",columns={"nameShort"}),
 *     @ORM\UniqueConstraint(name="sequence",columns={"sequenceNumber"})
 * })
 * @UniqueEntity({"name"})
 * @UniqueEntity({"nameShort"})
 * @UniqueEntity({"sequenceNumber"})
 */
class INDescriptor implements EntityInterface
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
     * @Assert\Length(max=50)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=5, name="nameShort")
     * @Assert\Length(max=5)
     * @Assert\NotBlank()
     */
    private $nameShort;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", name="sequenceNumber", columnDefinition="INT(3)")
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
     * @return INDescriptor
     */
    public function setId(?int $id): INDescriptor
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
     * @return INDescriptor
     */
    public function setName(?string $name): INDescriptor
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
     * @return INDescriptor
     */
    public function setNameShort(?string $nameShort): INDescriptor
    {
        $this->nameShort = $nameShort;
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
     * @return INDescriptor
     */
    public function setDescription(?string $description): INDescriptor
    {
        $this->description = $description;
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
     * @return INDescriptor
     */
    public function setSequenceNumber(?int $sequenceNumber): INDescriptor
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    /**
     * toArray
     * @param string|null $name
     * @return array
     */
    public function toArray(?string $name = null): array
    {
        return [
            'name' => $this->getName(),
            'sequence' => $this->getSequenceNumber(),
            'abbr' => $this->getNameShort(),
            'description' => $this->getDescription(),
            'canDelete' => ProviderFactory::create(INDescriptor::class)->canDelete($this),
            'id' => $this->getId(),
        ];
    }
}