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
 * Date: 17/01/2020
 * Time: 08:27
 */

namespace Kookaburra\SchoolAdmin\Pagination;

use App\Manager\Entity\PaginationAction;
use App\Manager\Entity\PaginationColumn;
use App\Manager\Entity\PaginationFilter;
use App\Manager\Entity\PaginationRow;
use App\Manager\PaginationInterface;
use App\Manager\AbstractPaginationManager;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\AttendanceCode;

/**
 * Class AttendanceCodePagination
 * @package Kookaburra\SchoolAdmin\Pagination
 */
class AttendanceCodePagination extends AbstractPaginationManager
{
    /**
     * execute
     * @return PaginationInterface
     */
    public function execute(): PaginationInterface
    {
        TranslationsHelper::setDomain('SchoolAdmin');
        $row = new PaginationRow();

        $column = new PaginationColumn();
        $column->setLabel('Code')
            ->setContentKey('code')
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Name')
            ->setContentKey('name')
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Direction')
            ->setContentKey('direction')
            ->setClass('column relative pr-4 cursor-pointer widthAuto');
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Scope')
            ->setContentKey('scope')
            ->setClass('column relative pr-4 cursor-pointer widthAuto');
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Active')
            ->setContentKey('active')
            ->setClass('column relative pr-4 cursor-pointer widthAuto');
        $row->addColumn($column);

        $action = new PaginationAction();
        $action->setTitle('Edit')
            ->setAClass('thickbox p-3 sm:p-0')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('fas fa-edit fa-fw fa-1-5x text-gray-800 hover:text-purple-500')
            ->setRoute('school_admin__attendance_code_edit')
            ->setRouteParams(['code' => 'id']);
        $row->addAction($action);

        $action = new PaginationAction();
        $action->setTitle('Delete')
            ->setAClass('thickbox p-3 sm:p-0')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('far fa-trash-alt fa-fw fa-1-5x text-gray-700 hover:text-red-500')
            ->setRoute('school_admin__attendance_code_delete')
            ->setDisplayWhen('canDelete')
            ->setOnClick('areYouSure')
            ->setRouteParams(['code' => 'id']);
        $row->addAction($action);

        foreach(AttendanceCode::getDirectionList() as $direction)
        {
            $filter = new PaginationFilter();
            $filter->setName('Direction: ' . $direction)
                ->setValue($direction)
                ->setGroup('Direction')
                ->setContentKey('direction');
            $row->addFilter($filter);
        }

        $filter = new PaginationFilter();
        $filter->setName('Scope: On Site' )
            ->setValue('Onsite')
            ->setGroup('Scope')
            ->setContentKey('scope_filter');
        $row->addFilter($filter);

        $filter = new PaginationFilter();
        $filter->setName('Scope: Off Site' )
            ->setValue('Offsite')
            ->setGroup('Scope')
            ->setContentKey('scope_filter');
        $row->addFilter($filter);

        $this->setRow($row);
        return $this;
    }
}
