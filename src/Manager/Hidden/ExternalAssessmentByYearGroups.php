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
 * Date: 20/03/2020
 * Time: 09:51
 */

namespace Kookaburra\SchoolAdmin\Manager\Hidden;

use Kookaburra\SystemAdmin\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Util\ErrorMessageHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Kookaburra\SchoolAdmin\Entity\YearGroup;

/**
 * Class ExternalAssessmentByYearGroups
 * @package Kookaburra\SchoolAdmin\Manager
 */
class ExternalAssessmentByYearGroups
{
    /**
     * @var Collection
     */
    private $yearGroups;

    /**
     * ExternalAssessmentByYearGroup constructor.
     */
    public function __construct()
    {
        $this->getYearGroups();
    }


    /**
     * @param bool $refresh
     * @return Collection
     */
    public function getYearGroups(bool $refresh = false): Collection
    {
        if ($this->yearGroups === null || $refresh) {
            $this->yearGroups = new ArrayCollection();
            $yearGroups = ProviderFactory::create(Setting::class)->getSettingByScopeAsArray('School Admin', 'primaryExternalAssessmentByYearGroup');
            if (empty($yearGroups)) {
                $groups = ProviderFactory::getRepository(YearGroup::class)->findBy([], ['sequenceNumber' => 'ASC']);
                foreach($groups as $w) {
                    $yearGroups[$w->getId()] = null;
                }
            }
            foreach($yearGroups as $yg=>$details)
            {
                $w = new ExternalAssessmentByYearGroup();
                $w->setId(intval($yg));
                $w->setYearGroup(intval($yg));
                $w->setYearGroupName(ProviderFactory::create(YearGroup::class)->getYearGroupName($yg));
                if ($details !== null) {
                    $details = explode('-', $details);
                    $w->setExternalAssessment(intval($details[0]));
                    array_shift($details);
                    $details = implode('-', $details);
                    $w->setFieldSet($details);
                }
                $this->yearGroups->set($w->getYearGroup(), $w);
            }
        }
        return $this->yearGroups;
    }

    /**
     * handleRequest
     * @param array $content
     * @return array
     */
    public function handleRequest(array $content): array
    {
        $data = [];
        $data['status'] = 'success';
        $fieldSets = [];
        foreach($content['yearGroups'] as $q=>$w) {
            if (intval($w['externalAssessment']) > 0 && !empty($w['fieldSet'])) {
                $fieldSets[$q] = $w['externalAssessment'] . '-' . $w['fieldSet'];
            } else {
                $fieldSets[$q] = '';
            }
        }

        ProviderFactory::create(Setting::class)->setSettingByScope('School Admin', 'primaryExternalAssessmentByYearGroup', serialize($fieldSets));
        $data = ErrorMessageHelper::getSuccessMessage($data, true);
        $this->yearGroups = null;
        $this->getYearGroups(true);

        return $data;
    }
}