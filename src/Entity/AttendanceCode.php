<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace Kookaburra\SchoolAdmin\Entity;

use App\Manager\EntityInterface;
use App\Manager\Traits\BooleanList;
use App\Util\TranslationsHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class AttendanceCode
 * @package Kookaburra\SchoolAdmin\Entity
 * @ORM\Entity(repositoryClass="Kookaburra\SchoolAdmin\Repository\AttendanceCodeRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="AttendanceCode",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="name",columns={"name"}),
 *     @ORM\UniqueConstraint(name="code",columns={"code"})})
 * @UniqueEntity({"name"})
 * @UniqueEntity({"code"})
 */
class AttendanceCode implements EntityInterface
{
    use BooleanList;

    /**
     * @var integer|null
     * @ORM\Id()
     * @ORM\Column(type="smallint", columnDefinition="INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(length=30)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(length=4)
     */
    private $code;

    /**
     * @var string|null
     * @ORM\Column(length=12)
     */
    private $type;

    /**
     * @var array
     */
    private static $typeList = ['Core', 'Additional'];

    /**
     * @var string|null
     * @ORM\Column(length=3)
     */
    private $direction;

    /**
     * @var array
     */
    private static $directionList = ['In','Out'];

    /**
     * @var string|null
     * @ORM\Column(length=14)
     */
    private $scope;

    /**
     * @var array
     */
    private static $scopeList = ['Onsite','Onsite - Late','Offsite','Offsite - Left'];

    /**
     * @var string|null
     * @ORM\Column(length=1)
     */
    private $active;

    /**
     * @var string|null
     * @ORM\Column(length=1)
     */
    private $reportable;

    /**
     * @var string|null
     * @ORM\Column(length=1)
     */
    private $future;

    /**
     * @var string|null
     * @ORM\Column(length=90, name="role_list", type="simple_array")
     */
    private $roleAll;

    /**
     * @var integer|null
     * @ORM\Column(type="smallint", name="sequence", columnDefinition="INT(3)")
     */
    private $sequenceNumber;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return AttendanceCode
     */
    public function setId(?int $id): AttendanceCode
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return AttendanceCode
     */
    public function setName(?string $name): AttendanceCode
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Code.
     *
     * @param string|null $code
     * @return AttendanceCode
     */
    public function setCode(?string $code): AttendanceCode
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return AttendanceCode
     */
    public function setType(?string $type): AttendanceCode
    {
        $this->type = in_array($type, self::getTypeList()) ? $type : '';
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * @param string|null $direction
     * @return AttendanceCode
     */
    public function setDirection(?string $direction): AttendanceCode
    {
        $this->direction = in_array($direction, self::getDirectionList()) ? $direction :  '';
        return $this;
    }

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param string|null $scope
     * @return AttendanceCode
     */
    public function setScope(?string $scope): AttendanceCode
    {
        $this->scope = in_array($scope, self::getScopeList()) ? $scope : '';
        return $this;
    }

    /**
     * isActive
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getActive() === 'Y';
    }

    /**
     * @return string|null
     */
    public function getActive(): ?string
    {
        return self::checkBoolean($this->active, 'N');
    }

    /**
     * @param string|null $active
     * @return AttendanceCode
     */
    public function setActive(?string $active): AttendanceCode
    {
        $this->active = self::checkBoolean($active, 'N');
        return $this;
    }

    /**
     * @return string|null
     */
    public function isReportable(): bool
    {
        return $this->getReportable() === 'Y';
    }

    /**
     * @return string|null
     */
    public function getReportable(): ?string
    {
        return self::checkBoolean($this->reportable, 'N');
    }

    /**
     * @param string|null $reportable
     * @return AttendanceCode
     */
    public function setReportable(?string $reportable): AttendanceCode
    {
        $this->reportable = self::checkBoolean($reportable,'N');
        return $this;
    }

    /**
     * isFuture
     * @return bool
     */
    public function isFuture(): bool
    {
        return $this->getFuture() === 'Y';
    }

    /**
     * @return string|null
     */
    public function getFuture(): ?string
    {
        return self::checkBoolean($this->future, 'N');
    }

    /**
     * @param string|null $future
     * @return AttendanceCode
     */
    public function setFuture(?string $future): AttendanceCode
    {
        $this->future = self::checkBoolean($future, 'N');
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRoleAll(): ?string
    {
        return $this->roleAll;
    }

    /**
     * @param string|null $roleAll
     * @return AttendanceCode
     */
    public function setRoleAll(?string $roleAll): AttendanceCode
    {
        $this->roleAll = $roleAll;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int
    {
        return $this->sequenceNumber;
    }

    /**
     * @param int|null $sequenceNumber
     * @return AttendanceCode
     */
    public function setSequenceNumber(?int $sequenceNumber): AttendanceCode
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
    }

    /**
     * @return array
     */
    public static function getDirectionList(): array
    {
        return self::$directionList;
    }

    /**
     * @return array
     */
    public static function getScopeList(): array
    {
        return self::$scopeList;
    }

    /**
     * toArray
     * @param string|null $name
     * @return array
     */
    public function toArray(?string $name = NULL): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'direction' => $this->getDirection(),
            'scope' => $this->getScope(),
            'scope_filter' => explode(' - ', $this->getScope())[0],
            'active' => $this->isActive() ? TranslationsHelper::translate('Yes', [], 'messages') :  TranslationsHelper::translate('No', [], 'messages'),
        ];
    }
}