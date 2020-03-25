<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 25/03/2020
 * Time: 11:43
 */

namespace Kookaburra\SchoolAdmin\Manager\Hidden;


class ExternalAssessmentByYearGroup
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $yearGroup;

    /**
     * @var string
     */
    private $yearGroupName;

    /**
     * @var integer|null
     */
    private $externalAssessment;

    /**
     * @var string|null
     */
    private $fieldSet;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Id.
     *
     * @param int $id
     * @return ExternalAssessmentByYearGroup
     */
    public function setId(int $id): ExternalAssessmentByYearGroup
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getYearGroup(): int
    {
        return $this->yearGroup;
    }

    /**
     * YearGroup.
     *
     * @param int $yearGroup
     * @return ExternalAssessmentByYearGroup
     */
    public function setYearGroup(int $yearGroup): ExternalAssessmentByYearGroup
    {
        $this->yearGroup = $yearGroup;
        return $this;
    }

    /**
     * @return string
     */
    public function getYearGroupName(): string
    {
        return $this->yearGroupName;
    }

    /**
     * YearGroupName.
     *
     * @param string $yearGroupName
     * @return ExternalAssessmentByYearGroup
     */
    public function setYearGroupName(string $yearGroupName): ExternalAssessmentByYearGroup
    {
        $this->yearGroupName = $yearGroupName;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getExternalAssessment(): ?int
    {
        return $this->externalAssessment;
    }

    /**
     * ExternalAssessment.
     *
     * @param int|null $externalAssessment
     * @return ExternalAssessmentByYearGroup
     */
    public function setExternalAssessment(?int $externalAssessment): ExternalAssessmentByYearGroup
    {
        $this->externalAssessment = $externalAssessment;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFieldSet(): ?string
    {
        return $this->fieldSet;
    }

    /**
     * FieldSet.
     *
     * @param string|null $fieldSet
     * @return ExternalAssessmentByYearGroup
     */
    public function setFieldSet(?string $fieldSet): ExternalAssessmentByYearGroup
    {
        $this->fieldSet = $fieldSet;
        return $this;
    }

}