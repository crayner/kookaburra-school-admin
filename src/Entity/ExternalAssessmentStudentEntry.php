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

use Kookaburra\SchoolAdmin\Entity\ScaleGrade;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ExternalAssessmentStudentEntry
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\ExternalAssessmentStudentEntryRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="ExternalAssessmentStudentEntry",
 *     indexes={
 *          @ORM\Index(name="external_assessment_student", columns={"external_assessment_student"}),
 *          @ORM\Index(name="external_assessment_field", columns={"external_assessment_field"}),
 *          @ORM\Index(name="scale_grade", columns={"scale_grade"})
 *     }
 * )
 */
class ExternalAssessmentStudentEntry
{
    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="bigint", columnDefinition="INT(14) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var ExternalAssessmentStudent|null
     * @ORM\ManyToOne(targetEntity="ExternalAssessmentStudent", inversedBy="entries")
     * @ORM\JoinColumn(name="external_assessment_student", referencedColumnName="id", nullable=false)
     */
    private $externalAssessmentStudent;

    /**
     * @var ExternalAssessmentField|null
     * @ORM\ManyToOne(targetEntity="ExternalAssessmentField")
     * @ORM\JoinColumn(name="external_assessment_field", referencedColumnName="id", nullable=false)
     */
    private $externalAssessmentField;

    /**
     * @var ScaleGrade|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\SchoolAdmin\Entity\ScaleGrade")
     * @ORM\JoinColumn(name="scale_grade", referencedColumnName="id")
     */
    private $scaleGrade;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return ExternalAssessmentStudentEntry
     */
    public function setId(?int $id): ExternalAssessmentStudentEntry
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return ExternalAssessmentStudent|null
     */
    public function getExternalAssessmentStudent(): ?ExternalAssessmentStudent
    {
        return $this->externalAssessmentStudent;
    }

    /**
     * @param ExternalAssessmentStudent|null $externalAssessmentStudent
     * @return ExternalAssessmentStudentEntry
     */
    public function setExternalAssessmentStudent(?ExternalAssessmentStudent $externalAssessmentStudent): ExternalAssessmentStudentEntry
    {
        $this->externalAssessmentStudent = $externalAssessmentStudent;
        return $this;
    }

    /**
     * @return ExternalAssessmentField|null
     */
    public function getExternalAssessmentField(): ?ExternalAssessmentField
    {
        return $this->externalAssessmentField;
    }

    /**
     * @param ExternalAssessmentField|null $externalAssessmentField
     * @return ExternalAssessmentStudentEntry
     */
    public function setExternalAssessmentField(?ExternalAssessmentField $externalAssessmentField): ExternalAssessmentStudentEntry
    {
        $this->externalAssessmentField = $externalAssessmentField;
        return $this;
    }

    /**
     * @return ScaleGrade|null
     */
    public function getScaleGrade(): ?ScaleGrade
    {
        return $this->scaleGrade;
    }

    /**
     * @param ScaleGrade|null $scaleGrade
     * @return ExternalAssessmentStudentEntry
     */
    public function setScaleGrade(?ScaleGrade $scaleGrade): ExternalAssessmentStudentEntry
    {
        $this->scaleGrade = $scaleGrade;
        return $this;
    }
}