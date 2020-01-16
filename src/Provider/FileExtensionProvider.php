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
 * Time: 17:34
 */

namespace Kookaburra\SchoolAdmin\Provider;

use App\Manager\Traits\EntityTrait;
use App\Provider\EntityProviderInterface;
use Kookaburra\SchoolAdmin\Entity\FileExtension;

/**
 * Class FileExtensionProvider
 * @package Kookaburra\SchoolAdmin\Provider
 */
class FileExtensionProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = FileExtension::class;

    /**
     * canDelete
     * @param FileExtension $extension
     * @return bool
     */
    public function canDelete(FileExtension $extension)
    {
        return true;
    }
}