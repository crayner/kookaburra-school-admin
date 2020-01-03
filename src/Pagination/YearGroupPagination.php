<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 3/01/2020
 * Time: 12:02
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

class YearGroupPagination extends ReactPaginationManager
{
    public function execute(): ReactPaginationInterface
    {
        TranslationsHelper::setDomain('SchoolAdmin');
        $row = new PaginationRow();

        $column = new PaginationColumn();
        $column->setLabel('Sequence')
            ->setContentKey('sequence')
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Name')
            ->setContentKey('name')
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Abbreviation')
            ->setContentKey('abbr')
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Head of Year')
            ->setContentKey('head')
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre')
        ;
        $row->addColumn($column);

        $action = new PaginationAction();
        $action->setTitle('Edit')
            ->setAClass('')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('fas fa-edit fa-fw fa-1-5x text-gray-700')
            ->setRoute('school_admin__year_group_edit')
            ->setRouteParams(['year' => 'id']);
        $row->addAction($action);

        $action = new PaginationAction();
        $action->setTitle('Delete')
            ->setAClass('')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('far fa-trash-alt fa-fw fa-1-5x text-gray-700')
            ->setRoute('school_admin__year_group_delete')
            ->setDisplayWhen('canDelete')
            ->setRouteParams(['year' => 'id']);
        $row->addAction($action);

        $action = new PaginationAction();
        $action->setTitle('Top of List')
            ->setAClass('')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('far fa-caret-square-up fa-fw fa-1-5x text-gray-700')
            ->setRoute('school_admin__year_group_top_of_list')
            ->setRouteParams(['year' => 'id']);
        $row->addAction($action);

        $this->setSortList(true)->setRow($row);

        return $this;
    }

}