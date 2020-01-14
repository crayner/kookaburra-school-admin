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
 * Date: 10/01/2020
 * Time: 07:59
 */

namespace Kookaburra\SchoolAdmin\Provider;


use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use App\Util\ErrorMessageHelper;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;

class ScaleGradeProvider implements EntityProviderInterface
{
    use EntityTrait;

    private $entityName = ScaleGrade::class;

    /**
     * saveGrades
     * @param array $grades
     * @param array $data
     * @return array
     */
    public function saveGrades(array $grades, array $data): array
    {
        $sm = $this->getEntityManager()->getConnection()->getSchemaManager();
        $prefix = $this->getEntityManager()->getConnection()->getParams()['driverOptions']['prefix'];

        $table = $sm->listTableDetails($prefix. 'ScaleGrade');
        $index = $table->getIndex('scalesequence');

        try {
            $sm->dropIndex($index, $table);

            foreach ($grades as $grade)
                $this->getEntityManager()->persist($grade);
            $this->getEntityManager()->flush();

            $sm->createIndex($index, $table);
        } catch (\Exception $e) {
            $data = ErrorMessageHelper::getDatabaseErrorMessage($data, true);
        }

        return $data;
    }
}