<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/10/2019
 * Time: 14:54
 */

namespace Kookaburra\SchoolAdmin\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class SpecialDay
 * @package Kookaburra\SchoolAdmin\Validator
 * @Annotation
 */
class SpecialDay extends Constraint
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