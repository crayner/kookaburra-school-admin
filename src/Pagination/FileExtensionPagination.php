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
 * Date: 16/01/2020
 * Time: 17:15
 */

namespace Kookaburra\SchoolAdmin\Pagination;

use App\Manager\Entity\PaginationAction;
use App\Manager\Entity\PaginationColumn;
use App\Manager\Entity\PaginationFilter;
use App\Manager\Entity\PaginationRow;
use App\Manager\PaginationInterface;
use App\Manager\AbstractPaginationManager;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\FileExtension;

class FileExtensionPagination extends AbstractPaginationManager
{
    public function execute(): PaginationInterface
    {
        TranslationsHelper::setDomain('SchoolAdmin');
        $row = new PaginationRow();

        $column = new PaginationColumn();
        $column->setLabel('Extension')
            ->setContentKey('extension')
            ->setSort(true)
            ->setSearch(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Name')
            ->setContentKey('name')
            ->setSearch(true)
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto')
        ;
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Type')
            ->setSort(true)
            ->setContentKey('type')
            ->setClass('column relative pr-4 cursor-pointer widthAuto');
        $row->addColumn($column);

        $action = new PaginationAction();
        $action->setTitle('Edit')
            ->setAClass('thickbox p-3 sm:p-0')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('fas fa-edit fa-fw fa-1-5x text-gray-800 hover:text-purple-500')
            ->setRoute('school_admin__file_extensions_edit')
            ->setRouteParams(['fileExtension' => 'id']);
        $row->addAction($action);

        $action = new PaginationAction();
        $action->setTitle('Delete')
            ->setAClass('thickbox p-3 sm:p-0')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('far fa-trash-alt fa-fw fa-1-5x text-gray-800 hover:text-red-500')
            ->setRoute('school_admin__file_extensions_delete')
            ->setDisplayWhen('canDelete')
            ->setRouteParams(['fileExtension' => 'id']);
        $row->addAction($action);

        foreach(FileExtension::getTypeList() as $type) {
            $filter = new PaginationFilter();
            $filter->setName('Type: ' . $type)
                ->setValue($type)
                ->setGroup('Type')
                ->setContentKey('type');
            $row->addFilter($filter);
        }

        $this->setRow($row);
        return $this;
    }
}
