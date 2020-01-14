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
 * Date: 12/01/2020
 * Time: 16:34
 */

namespace Kookaburra\SchoolAdmin\Pagination;

use App\Manager\Entity\PaginationAction;
use App\Manager\Entity\PaginationColumn;
use App\Manager\Entity\PaginationFilter;
use App\Manager\Entity\PaginationRow;
use App\Manager\ReactPaginationInterface;
use App\Manager\ReactPaginationManager;
use App\Util\TranslationsHelper;

/**
 * Class ScaleGradePagination
 * @package Kookaburra\SchoolAdmin\Pagination
 */
class ScaleGradePagination extends ReactPaginationManager
{
    /**
     * execute
     * @return ReactPaginationInterface
     */
    public function execute(): ReactPaginationInterface
    {
        TranslationsHelper::setDomain('SchoolAdmin');
        $row = new PaginationRow();
        $this->setTargetElement('scaleGradePaginationContent');

        $column = new PaginationColumn();
        $column->setLabel('Value')
            ->setSort(true)
            ->setContentKey('value')
            ->setClass('column relative pr-4 cursor-pointer widthAuto')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Descriptor')
            ->setContentKey('descriptor')
            ->setClass('column relative pr-4 cursor-pointer widthAuto')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Is Default?')
            ->setContentKey('default')
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-center');
        $row->addColumn($column);

        $action = new PaginationAction();
        $action->setTitle('Edit')
            ->setAClass('')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('fas fa-edit fa-fw fa-1-5x text-gray-700')
            ->setRoute('school_admin__scale_grade_edit')
            ->setRouteParams(['grade' => 'id', 'scale' => 'scaleId']);
        $row->addAction($action);

        $action = new PaginationAction();
        $action->setTitle('Delete')
            ->setAClass('')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('far fa-trash-alt fa-fw fa-1-5x text-gray-700')
            ->setRoute('school_admin__scale_grade_delete')
            ->setDisplayWhen('canDelete')
            ->setOnClick('areYouSure')
            ->setRouteParams(['grade' => 'id', 'scale' => 'scaleId']);
        $row->addAction($action);

        $this
            ->setPageMax(50)
            ->setRow($row)
            ->setDraggableSort(true)
            ->setDraggableRoute('school_admin__scale_grade_sort');

        return $this;
    }
}