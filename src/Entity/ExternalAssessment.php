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
use App\Util\TranslationsHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ExternalAssessment
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\ExternalAssessmentRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="ExternalAssessment")
 */
class ExternalAssessment implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="smallint", columnDefinition="INT(4) UNSIGNED AUTO_INCREMENT")
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
     * @ORM\Column(length=10, name="nameShort")
     * @Assert\Length(max=10)
     * @Assert\NotBlank()
     */
    private $nameShort;

    /**
     * @var string|null
     * @ORM\Column()
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var string|null
     * @ORM\Column(type="text",nullable=true)
     * @Assert\Url()
     */
    private $website;

    /**
     * @var string|null
     * @ORM\Column(length=1)
     * @Assert\Choice(callback="getBooleanList")
     */
    private $active = 'N';

    /**
     * @var string|null
     * @ORM\Column(length=1, name="allowFileUpload", options={"default": "N"})
     * @Assert\Choice(callback="getBooleanList")
     */
    private $allowFileUpload = 'N';

    /**
     * @var Collection|ExternalAssessmentField[]
     * @ORM\OneToMany(targetEntity="Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField",mappedBy="externalAssessment")
     */
    private $fields;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ExternalAssessment
     */
    public function setId(?int $id): ExternalAssessment
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
     * @return ExternalAssessment
     */
    public function setName(?string $name): ExternalAssessment
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
     * @return ExternalAssessment
     */
    public function setNameShort(?string $nameShort): ExternalAssessment
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
     * @return ExternalAssessment
     */
    public function setDescription(?string $description): ExternalAssessment
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     * @return ExternalAssessment
     */
    public function setWebsite(?string $website): ExternalAssessment
    {
        $this->website = $website;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getActive() === 'Y';
    }

    /**
     * @return string
     */
    public function getActive(): string
    {
        return $this->active = self::checkBoolean($this->active, 'N');
    }

    /**
     * @param string|null $active
     * @return ExternalAssessment
     */
    public function setActive(?string $active): ExternalAssessment
    {
        $this->active = self::checkBoolean($active, 'N');
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowFileUpload(): bool
    {
        return $this->getAllowFileUpload() === 'Y';
    }

    /**
     * @return string|null
     */
    public function getAllowFileUpload(): ?string
    {
        return $this->allowFileUpload =  self::checkBoolean($this->allowFileUpload, 'N');
    }

    /**
     * @param string|null $allowFileUpload
     * @return ExternalAssessment
     */
    public function setAllowFileUpload(?string $allowFileUpload): ExternalAssessment
    {
        $this->allowFileUpload = self::checkBoolean($allowFileUpload, 'N');
        return $this;
    }

    /**
     * @return Collection|ExternalAssessmentField[]
     */
    public function getFields(): Collection
    {
        if (null === $this->fields)
            $this->fields = new ArrayCollection();

        if ($this->fields instanceof PersistentCollection)
            $this->fields->initialize();

        return $this->fields;
    }

    /**
     * Fields.
     *
     * @param Collection|ExternalAssessmentField[] $fields
     * @return ExternalAssessment
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * getFieldChoices
     * @return array
     */
    public function getFieldChoices(): array
    {
        $result = [];
        foreach($this->getFields() as $field)
        {
            $result[$field->getCategory()] = ltrim(substr($field->getCategory(), intval(mb_strpos($field->getCategory(), '_'))), '_');
        }
        return $result;
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
            'abbr' => $this->getNameShort(),
            'description' => $this->getDescription(),
            'active' => $this->isActive() ? TranslationsHelper::translate('Yes', [], 'messages') : TranslationsHelper::translate('No', [], 'messages'),
            'upload' => $this->isAllowFileUpload() ? TranslationsHelper::translate('Yes', [], 'messages') : TranslationsHelper::translate('No', [], 'messages'),
            'canDelete' => ProviderFactory::create(ExternalAssessment::class)->canDelete($this),
        ];
    }
}