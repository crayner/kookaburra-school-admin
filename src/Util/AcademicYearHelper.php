<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/09/2019
 * Time: 10:01
 */

namespace Kookaburra\SchoolAdmin\Util;

use App\Provider\ProviderFactory;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AcademicYearHelper
 * @package App\Util
 */
class AcademicYearHelper
{
    /**
     * @var RequestStack
     */
    private static $stack;

    /**
     * GlobalHelper constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        self::$stack = $stack;
    }

    /**
     * getCurrentSchoolYear
     * @return mixed
     */
    public static function getCurrentAcademicYear()
    {
        $session = self::$stack->getCurrentRequest()->getSession();
        if ($session->has('academicYear'))
            return $session->get('academicYear');
        return ProviderFactory::getRepository(AcademicYear::class)->findOneByStatus('Current');
    }
}