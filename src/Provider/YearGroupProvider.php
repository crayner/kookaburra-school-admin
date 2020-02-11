<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 10/09/2019
 * Time: 18:01
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Entity\StudentEnrolment;
use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

/**
 * Class YearGroupProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class YearGroupProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = YearGroup::class;

    /**
     * getCurrentYearGroupChoiceList
     * @return array
     */
    public function getCurrentYearGroupChoiceList(): array {
        $result = [];
        foreach($this->getRepository()->findCurrentYearGroups() as $q=>$w){
            $result[]= new ChoiceView([], $w->getId(), $w->getName(), []);
        }
        return $result;
    }

    public function moveToTopOfList(YearGroup $year)
    {
        $years = $this->getRepository()->findBy([], ['sequenceNumber' => 'ASC']);
        $last = end($years);
        $offset = $last->getSequenceNumber() + 1;
        $x = $offset + 1;
        foreach($years as $q=>$entity) {
            if ($entity->getId() !== $year->getId()) {
                $entity->setSequenceNumber($x++);
            } else {
                $entity->setSequenceNumber($offset);
            }
            $years[$q] = $entity;
        }
        try {
            foreach ($years as $entity)
                $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        } catch (\PDOException | PDOException | UniqueConstraintViolationException $e) {
            $this->getMessageManager()->add('error', 'return.error.2', [], 'messages');
            return ;
        }

        $offset = 1;
        $x = $offset + 1;
        foreach($years as $q=>$entity) {
            if ($entity->getId() !== $year->getId()) {
                $entity->setSequenceNumber($x++);
            } else {
                $entity->setSequenceNumber($offset);
            }
            $years[$q] = $entity;
        }
        try {
            foreach ($years as $entity)
                $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        } catch (\PDOException | PDOException | UniqueConstraintViolationException $e) {
            $this->getMessageManager()->add('error', 'return.error.2', [], 'messages');
            return ;
        }
        $this->getMessageManager()->add('success', 'return.success.0', [], 'messages');
        sleep(0.5);
    }

    /**
     * canDelete
     * @param YearGroup $year
     * @return bool
     */
    public function canDelete(YearGroup $year): bool
    {
        return $this->getRepository(StudentEnrolment::class)->countEnrolmentsByYearGroup($year) === 0;
    }

    /**
     * @var array|null
     */
    private $allYears;

    /**
     * findAll
     * @return array
     */
    public function findAll(): array
    {
        if (null === $this->allYears) {
            foreach($this->getRepository()->findAll() as $year) {
                $this->allYears[intval($year->getId())] = $year;
            }
        }
        return $this->allYears;
    }
}