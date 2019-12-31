<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 5/07/2019
 * Time: 11:35
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class AcademicYearProvider
 * @package Kookaburra\SchoolProvider\Provider
 */
class AcademicYearProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = AcademicYear::class;

    /**
     * setCurrentAcademicYear
     * @param SessionInterface $session
     * @return object|null
     * @throws \Exception
     */
    public function setCurrentAcademicYear(SessionInterface $session)
    {
        $row = $this->getRepository()->findOneBy(['status' => 'Current']);

        //Check number of rows returned.
        if (!$row instanceof AcademicYear) {
            die(__('Configuration Error: there is a problem accessing the current Academic Year from the database.'));
        }
        
        $session->set('AcademicYearID',$row->getId());
        $session->set('AcademicYear', $row);
        $session->set('AcademicYearSequenceNumber', $row->getSequenceNumber());
        $session->set('AcademicYearFirstDay', $row->getFirstDay());
        $session->set('AcademicYearLastDay', $row->getLastDay());
        return $row;
    }

    /**
     * isAcademicYearOverlap
     * @param AcademicYear $year
     * @return bool
     */
    public function isAcademicYearOverlap(AcademicYear $year): bool
    {
        $result = $this->getRepository()->findAllByOverlap($year);
        return $result === [] ? false : true;
    }

    /**
     * selectAcademicYears
     * @param string $status
     * @param string $direction
     * @return array
     */
    public function selectAcademicYears(string $status = 'All', $direction = 'ASC'): array
    {
        $result = [];
        foreach($this->getRepository()->findByStatus($status, $direction) as $item)
        {
            $result[$item['name']] = $item['id'];
        }
        return $result;
    }
}