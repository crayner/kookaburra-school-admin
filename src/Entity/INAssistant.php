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
 * Class INAssistant
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\INAssistantRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="INAssistant",
 *     indexes={@ORM\Index(name="student",columns={"student"}),
 *     @ORM\Index(name="assistant",columns={"assistant"})})
 */
class INAssistant
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", columnDefinition="INT(10) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="student", referencedColumnName="id", nullable=false)
     */
    private $student;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="assistant", referencedColumnName="id", nullable=false)
     */
    private $assistant;

    /**
     * @var string|null
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return INAssistant
     */
    public function setId(?int $id): INAssistant
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getStudent(): ?Person
    {
        return $this->student;
    }

    /**
     * @param Person|null $student
     * @return INAssistant
     */
    public function setStudent(?Person $student): INAssistant
    {
        $this->student = $student;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getAssistant(): ?Person
    {
        return $this->assistant;
    }

    /**
     * @param Person|null $assistant
     * @return INAssistant
     */
    public function setAssistant(?Person $assistant): INAssistant
    {
        $this->assistant = $assistant;
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
     * @return INAssistant
     */
    public function setComment(?string $comment): INAssistant
    {
        $this->comment = $comment;
        return $this;
    }
}