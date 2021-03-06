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
 * Time: 09:47
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\EnumType;
use App\Form\Type\HeaderType;
use App\Form\Type\ReactFormType;
use Kookaburra\SystemAdmin\Form\SettingsType;
use App\Form\Type\ToggleType;
use App\Provider\ProviderFactory;
use Kookaburra\SchoolAdmin\Entity\AttendanceCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AttendanceReasonsType
 * @package Kookaburra\SchoolAdmin\Form
 */
class AttendanceContextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('header', HeaderType::class,
                [
                    'label' => 'Context and Defaults',
                ]
            )
            ->add('settings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Attendance',
                            'name' => 'countClassAsSchool',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Attendance',
                            'name' => 'crossFillClasses',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Attendance',
                            'name' => 'defaultRollGroupAttendanceType',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_class' => AttendanceContextType::class,
                                'choice_list_method' => 'getAttendanceTypeList',
                            ],
                        ],
                        [
                            'scope' => 'Attendance',
                            'name' => 'defaultClassAttendanceType',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_class' => AttendanceContextType::class,
                                'choice_list_method' => 'getAttendanceTypeList',
                                'choice_list_prefix' => 'attendancecontexttype.attendance__defaultrollgroupattendancetype',
                            ],
                        ],
                    ],
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Submit',
                    'translation_domain' => 'messages',
                ]
            )
        ;
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'SchoolAdmin',
                'data_class' => null,
            ]
        );
    }

    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return ReactFormType::class;
    }

    /**
     * getAttendanceTypeList
     * @return array
     */
    public static function getAttendanceTypeList(): array
    {
        $result = ProviderFactory::getRepository(AttendanceCode::class)->findAttendanceTypeList();
        $x = [];
        foreach($result as $w)
            $x[] = $w['name'];
        return $x;
    }
}