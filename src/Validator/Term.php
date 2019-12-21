<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 21/12/2019
 * Time: 20:44
 */

namespace Kookaburra\SchoolAdmin\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Term
 * @package Kookaburra\SchoolAdmin\Validator
 * @Annotation
 */
class Term extends Constraint
{
    public $transDomain = 'SchoolAdmin';

    /**
     * getTargets
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}