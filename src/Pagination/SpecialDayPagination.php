<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/12/2019
 * Time: 12:38
 */

namespace Kookaburra\SchoolAdmin\Pagination;


use App\Manager\Entity\PaginationAction;
use App\Manager\Entity\PaginationColumn;
use App\Manager\Entity\PaginationFilter;
use App\Manager\Entity\PaginationRow;
use App\Manager\ReactPaginationInterface;
use App\Manager\ReactPaginationManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Util\AcademicYearHelper;

class SpecialDayPagination extends ReactPaginationManager
{
    public function execute(): ReactPaginationInterface
    {
        TranslationsHelper::setDomain('SchoolAdmin');
        $row = new PaginationRow();

        $column = new PaginationColumn();
        $column->setLabel('Academic Year')
            ->setContentKey('year')
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Date')
            ->setContentKey('date')
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Name')
            ->setContentKey(['name','description'])
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Type')
            ->setContentKey('type')
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre')
        ;
        $row->addColumn($column);

        $action = new PaginationAction();
        $action->setTitle('Edit')
            ->setAClass('')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('fas fa-edit fa-fw fa-1-5x text-gray-700')
            ->setRoute('school_admin__special_day_edit')
            ->setRouteParams(['day' => 'id']);
        $row->addAction($action);

        $action = new PaginationAction();
        $action->setTitle('Delete')
            ->setAClass('')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('far fa-trash-alt fa-fw fa-1-5x text-gray-700')
            ->setRoute('school_admin__special_day_delete')
            ->setDisplayWhen('canDelete')
            ->setOnClick('areYouSure')
            ->setRouteParams(['day' => 'id']);
        $row->addAction($action);

        foreach(ProviderFactory::getRepository(AcademicYear::class)->findBy([], ['firstDay' => 'ASC']) as $year) {
            $filter = new PaginationFilter();
            $filter->setName('Academic Year: ' . $year->getName())
                ->setValue($year->getName())
                ->setLabel(['Academic Year: {value}', ['{value}' => $year->getName()], 'SchoolAdmin'])
                ->setGroup('Academic Year')
                ->setContentKey('year');
            $row->addFilter($filter);
        }

        $year = AcademicYearHelper::getCurrentAcademicYear();
        $row->setDefaultFilter(['Academic Year: ' . $year->getName()]);
        $this->setRow($row);
dump($this);
        return $this;
    }
}