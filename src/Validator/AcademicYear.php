<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/10/2019
 * Time: 13:44
 */

namespace Kookaburra\SchoolAdmin\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class AcademicYear
 * @package Kookaburra\SchoolAdmin\Validator
 * @Annotation
 */
class AcademicYear extends Constraint
{
    /**
     * getTargets
     * @return array|string
     */
    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}