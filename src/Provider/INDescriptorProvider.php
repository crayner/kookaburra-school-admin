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
 * Date: 18/01/2020
 * Time: 10:15
 */

namespace Kookaburra\SchoolAdmin\Provider;


use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\INArchive;
use Kookaburra\SchoolAdmin\Entity\INDescriptor;
use Kookaburra\SchoolAdmin\Entity\INPersonDescriptor;

class INDescriptorProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = INDescriptor::class;

    /**
     * canDelete
     * @param INDescriptor $descriptor
     * @return bool
     */
    public function canDelete(INDescriptor $descriptor)
    {
        if ($this->getRepository(INPersonDescriptor::class)->countDescriptor($descriptor) !== 0)
            return false;
        if ($this->getRepository(INArchive::class)->countDescriptor($descriptor) !== 0)
            return false;
        return true;
    }
}