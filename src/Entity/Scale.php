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
use App\Util\TranslationsHelper;
use App\Validator as Check;
use App\Manager\Traits\BooleanList;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Scale
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\ScaleRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="Scale",
 *     indexes={@ORM\Index(name="lowestAcceptable",columns={"lowestAcceptable"})})
 */
class Scale implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="smallint", columnDefinition="INT(5) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=40)
     * @Assert\NotBlank()
     * @Assert\Length(max=40)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=5, name="nameShort")
     * @Assert\NotBlank()
     * @Assert\Length(max=5)
     */
    private $nameShort;

    /**
     * @var string|null
     * @ORM\Column(length=50,name="usage_info")
     * @Assert\Length(max=50)
     */
    private $usage;

    /**
     * @var ScaleGrade|null
     * @ORM\OneToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\ScaleGrade")
     * @ORM\JoinColumn(name="lowestAcceptable", referencedColumnName="id", nullable=true)
     */
    private $lowestAcceptable;

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "Y"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $active = 'Y';

    /**
     * @var string|null
     * @ORM\Column(length=1, options={"default": "N"},name="is_numeric")
     * @Assert\Choice(callback="getBooleanList")
     */
    private $numeric = 'N';

    /**
     * @var ScaleGrade|null
     * @ORM\OneToMany(targetEntity="ScaleGrade", mappedBy="scale")
     */
    private $scaleGrades;

    /**
     * Scale constructor.
     */
    public function __construct()
    {
        $this->scaleGrades = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Scale
     */
    public function setId(?int $id): Scale
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
     * @return Scale
     */
    public function setName(?string $name): Scale
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
     * @return Scale
     */
    public function setNameShort(?string $nameShort): Scale
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsage(): ?string
    {
        return $this->usage;
    }

    /**
     * @param string|null $usage
     * @return Scale
     */
    public function setUsage(?string $usage): Scale
    {
        $this->usage = $usage;
        return $this;
    }

    /**
     * @return ScaleGrade|null
     */
    public function getLowestAcceptable(): ?ScaleGrade
    {
        return $this->lowestAcceptable;
    }

    /**
     * LowestAcceptable.
     *
     * @param ScaleGrade|null $lowestAcceptable
     * @return Scale
     */
    public function setLowestAcceptable(?ScaleGrade $lowestAcceptable): Scale
    {
        $this->lowestAcceptable = $lowestAcceptable;
        return $this;
    }

    /**
     * isActive
     * @return bool
     */
    public function isActive(): bool
    {
        return$this->getActive() === 'Y';
    }

    /**
     * getActive
     * @return string
     */
    public function getActive(): string
    {
        return self::checkBoolean($this->active);
    }

    /**
     * @param string|null $active
     * @return Scale
     */
    public function setActive(?string $active): Scale
    {
        $this->active = self::checkBoolean($active);
        return $this;
    }

    /**
     * isNumeric
     * @return bool
     */
    public function isNumeric(): bool
    {
        return $this->getNumeric() === 'Y';
    }

    /**
     * getNumeric
     * @return string
     */
    public function getNumeric(): string
    {
        return self::checkBoolean($this->numeric);
    }

    /**
     * @param string|null $numeric
     * @return Scale
     */
    public function setNumeric(?string $numeric): Scale
    {
        $this->numeric = self::checkBoolean($numeric, 'N');
        return $this;
    }

    /**
     * @return Scale|null
     */
    public function getScaleGrades(): ?Scale
    {
        return $this->scaleGrades;
    }

    /**
     * ScaleGrades.
     *
     * @param Scale|null $scaleGrades
     * @return Scale
     */
    public function setScaleGrades(?Scale $scaleGrades): Scale
    {
        $this->scaleGrades = $scaleGrades;
        return $this;
    }

    /**
     * __toString
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() . ' ('. $this->getNameShort() .')';
    }

    /**
     * toArray
     * @param string|null $name
     * @return array
     */
    public function toArray(?string $name = null): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'usage' => $this->getUsage(),
            'abbr' => $this->getNameShort(),
            'isActive' => $this->isActive(),
            'active' => $this->isActive() ? TranslationsHelper::translate('Yes', [], 'messages') : TranslationsHelper::translate('No', [], 'messages'),
            'numeric' => $this->isNumeric() ? TranslationsHelper::translate('Yes', [], 'messages') : TranslationsHelper::translate('No', [], 'messages'),
            'canDelete' => ProviderFactory::create(Scale::class)->canDelete($this),
        ];
    }

    public function getScaleId(): ?int
    {
        return $this->getId();
    }
}