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

use App\Entity\Scale;
use App\Manager\EntityInterface;
use App\Provider\ProviderFactory;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ExternalAssessmentField
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\ExternalAssessmentFieldRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="ExternalAssessmentField",
 *     indexes={@ORM\Index(name="external_assessment", columns={"external_assessment"})})
 */
class ExternalAssessmentField implements EntityInterface
{
    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="integer", columnDefinition="INT(6) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var ExternalAssessment|null
     * @ORM\ManyToOne(targetEntity="ExternalAssessment")
     * @ORM\JoinColumn(name="external_assessment", referencedColumnName="id", nullable=false)
     */
    private $externalAssessment;

    /**
     * @var string|null
     * @ORM\Column(length=50)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=50)
     */
    private $category;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", columnDefinition="INT(4)")
     */
    private $order;

    /**
     * @var Scale|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Scale")
     * @ORM\JoinColumn(name="gibbonScaleID", referencedColumnName="gibbonScaleID", nullable=false)
     */
    private $scale;

    /**
     * @var string|null
     * @ORM\Column(name="year_group_list", nullable=true, type="simple_array")
     */
    private $yearGroupList;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ExternalAssessmentField
     */
    public function setId(?int $id): ExternalAssessmentField
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return ExternalAssessment|null
     */
    public function getExternalAssessment(): ?ExternalAssessment
    {
        return $this->externalAssessment;
    }

    /**
     * @param ExternalAssessment|null $externalAssessment
     * @return ExternalAssessmentField
     */
    public function setExternalAssessment(?ExternalAssessment $externalAssessment): ExternalAssessmentField
    {
        $this->externalAssessment = $externalAssessment;
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
     * @return ExternalAssessmentField
     */
    public function setName(?string $name): ExternalAssessmentField
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     * @return ExternalAssessmentField
     */
    public function setCategory(?string $category): ExternalAssessmentField
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrder(): ?int
    {
        return $this->order;
    }

    /**
     * @param int|null $order
     * @return ExternalAssessmentField
     */
    public function setOrder(?int $order): ExternalAssessmentField
    {
        $this->order = $order;
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
     * @return ExternalAssessmentField
     */
    public function setScale(?Scale $scale): ExternalAssessmentField
    {
        $this->scale = $scale;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getYearGroupList(): ?string
    {
        return $this->yearGroupList;
    }

    /**
     * @param string|null $yearGroupList
     * @return ExternalAssessmentField
     */
    public function setYearGroupList(?string $yearGroupList): ExternalAssessmentField
    {
        $this->yearGroupList = $yearGroupList;
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
            'category' => $this->getCategory(),
            'order' => $this->getOrder(),
            'canDelete' => ProviderFactory::create(ExternalAssessmentField::class)->canDelete($this),
        ];
    }
}