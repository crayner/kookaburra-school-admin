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
 * Date: 21/12/2019
 * Time: 10:46
 */

namespace Kookaburra\SchoolAdmin\Pagination;

use App\Manager\Entity\PaginationAction;
use App\Manager\Entity\PaginationColumn;
use App\Manager\Entity\PaginationRow;
use App\Manager\PaginationInterface;
use App\Manager\AbstractPaginationManager;
use App\Util\TranslationsHelper;

/**
 * Class AcademicYearPagination
 * @package Kookaburra\SchoolAdmin\Pagination
 */
class AcademicYearPagination extends AbstractPaginationManager
{
    public function execute(): PaginationInterface
    {
        TranslationsHelper::setDomain('SchoolAdmin');
        $row = new PaginationRow();

        $column = new PaginationColumn();
        $column->setLabel('Name')
            ->setContentKey('name')
            ->setSort(true)
            ->setClass('p-2 sm:p-3')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Sequence')
            ->setContentKey('sequence')
            ->setSort(true)
            ->setClass('p-2 sm:p-3')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Dates')
            ->setContentKey(['dates'])
            ->setClass('p-2 sm:p-3 hidden sm:table-cell');
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Status')
            ->setContentKey(['status'])
            ->setClass('p-2 sm:p-3 hidden md:table-cell');
        $row->addColumn($column);

        $action = new PaginationAction();
        $action->setTitle('Edit')
            ->setAClass('thickbox p-3 sm:p-0')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('fas fa-edit fa-fw fa-1-5x text-gray-800 hover:text-purple-600')
            ->setRoute('school_admin__academic_year_edit')
            ->setRouteParams(['year' => 'id']);
        $row->addAction($action);

        $action = new PaginationAction();
        $action->setTitle('Delete')
            ->setAClass('thickbox p-3 sm:p-0')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('far fa-trash-alt fa-fw fa-1-5x text-gray-800 hover:text-red-600')
            ->setRoute('school_admin__academic_year_delete')
            ->setDisplayWhen('canDelete')
            ->setOnClick('areYouSure')
            ->setRouteParams(['year' => 'id']);
        $row->addAction($action);

        $action = new PaginationAction();
        $action->setTitle('Display')
            ->setAClass('thickbox p-3 sm:p-0')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('far fa-calendar-alt fa-fw fa-1-5x text-gray-800 hover:text-purple-600')
            ->setRoute('school_admin__academic_year_display')
            ->setOnClick('loadNewPage')
            ->setOptions('Calender Display')
            ->setRouteParams(['year' => 'id']);
        $row->addAction($action);

        $this->setRow($row);
        return $this;
    }
}