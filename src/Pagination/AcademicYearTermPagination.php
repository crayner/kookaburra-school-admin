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
 * Time: 20:01
 */

namespace Kookaburra\SchoolAdmin\Pagination;

use App\Manager\Entity\PaginationAction;
use App\Manager\Entity\PaginationColumn;
use App\Manager\Entity\PaginationFilter;
use App\Manager\Entity\PaginationRow;
use App\Manager\PaginationInterface;
use App\Manager\AbstractPaginationManager;
use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;

/**
 * Class AcademicYearTermPagination
 * @package Kookaburra\SchoolAdmin\Pagination
 */
class AcademicYearTermPagination extends AbstractPaginationManager
{
    public function execute(): PaginationInterface
    {
        TranslationsHelper::setDomain('SchoolAdmin');
        $row = new PaginationRow();

        $column = new PaginationColumn();
        $column->setLabel('Academic Year')
            ->setContentKey('year')
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre');
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Sequence')
            ->setContentKey('sequence')
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre');
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Name')
            ->setContentKey('name')
            ->setSort(true)
            ->setClass('column relative pr-4 cursor-pointer widthAuto text-centre');
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Abbreviation')
            ->setContentKey(['abbr'])
            ->setClass('column relative pr-4 cursor-pointer widthAuto');
        $row->addColumn($column);

        $column = new PaginationColumn();
        $column->setLabel('Dates')
            ->setContentKey(['dates'])
            ->setClass('column relative pr-4 cursor-pointer widthAuto');
        $row->addColumn($column);

        $action = new PaginationAction();
        $action->setTitle('Edit')
            ->setAClass('thickbox p-3 sm:p-0')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('fas fa-edit fa-fw fa-1-5x text-gray-800 hover:text-purple-500')
            ->setRoute('school_admin__academic_year_term_edit')
            ->setRouteParams(['term' => 'id']);
        $row->addAction($action);

        $action = new PaginationAction();
        $action->setTitle('Delete')
            ->setAClass('thickbox p-3 sm:p-0')
            ->setColumnClass('column p-2 sm:p-3')
            ->setSpanClass('far fa-trash-alt fa-fw fa-1-5x text-gray-800 hover:text-red-500' )
            ->setRoute('school_admin__academic_year_term_delete')
            ->setDisplayWhen('canDelete')
            ->setOnClick('areYouSure')
            ->setRouteParams(['term' => 'id']);
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

        $this->setRow($row);
        return $this;
    }
}