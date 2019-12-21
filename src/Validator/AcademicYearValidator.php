<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/10/2019
 * Time: 13:45
 */

namespace Kookaburra\SchoolAdmin\Validator;

use App\Provider\ProviderFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class AcademicYearValidator
 * @package Kookaburra\SchoolAdmin\Validator
 */
class AcademicYearValidator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof \Kookaburra\SchoolAdmin\Entity\AcademicYear)
            return ;

        if ($value->getFirstDay() >= $value->getLastDay())
            $this->context->buildViolation('The first day must be before the last day.')
                ->atPath('firstDay')
                ->setTranslationDomain('messages')
                ->addViolation();

        if (null === $value->getFirstDay())
            return;

        $last = new \DateTime($value->getFirstDay()->format('Y-m-d') . ' 00:00:00 +1 Year -1 Day');

        if ($value->getLastDay()->format('Y-m-d') !== $last->format('Y-m-d'))
            $this->context->buildViolation('The school academic year should cover a whole year.')
                ->atPath('lastDay')
                ->setTranslationDomain('messages')
                ->addViolation();
        if (ProviderFactory::create(\Kookaburra\SchoolAdmin\Entity\AcademicYear::class)->isAcademicYearOverlap($value))
            $this->context->buildViolation('The school academic year should not overlap another academic year.')
                ->atPath('name')
                ->setTranslationDomain('messages')
                ->addViolation();

    }
}