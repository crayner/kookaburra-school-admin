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
 * Date: 15/01/2020
 * Time: 14:45
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\HeaderType;
use App\Form\Type\ReactFormType;
use Kookaburra\SystemAdmin\Form\SettingsType;
use App\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PlannerSettingType
 * @package Kookaburra\SchoolAdmin\Form
 */
class PlannerSettingType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('templateHeader', HeaderType::class,
                [
                    'label' => 'Planner Templates',
                    'panel' => 'Templates',
                ]
            )
            ->add('templateSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Planner',
                            'name' => 'lessonDetailsTemplate',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Planner',
                            'name' => 'teachersNotesTemplate',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Planner',
                            'name' => 'unitOutlineTemplate',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Planner',
                            'name' => 'smartBlockTemplate',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                    ],
                    'panel' => 'Templates',
                ]
            )
            ->add('submit1', SubmitType::class,
                [
                    'label' => 'Submit',
                    'panel' => 'Templates',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('accessHeader', HeaderType::class,
                [
                    'label' => 'Access Settings',
                    'panel' => 'Access',
                ]
            )
            ->add('accessSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Planner',
                            'name' => 'makeUnitsPublic',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Planner',
                            'name' => 'shareUnitOutline',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Planner',
                            'name' => 'allowOutcomeEditing',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Planner',
                            'name' => 'sharingDefaultParents',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Planner',
                            'name' => 'sharingDefaultStudents',
                            'entry_type' => ToggleType::class,
                        ],
                    ],
                    'panel' => 'Access',
                ]
            )
            ->add('submit2', SubmitType::class,
                [
                    'label' => 'Submit',
                    'panel' => 'Access',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('miscellaneousHeader', HeaderType::class,
                [
                    'label' => 'Miscellaneous',
                    'panel' => 'Miscellaneous',
                ]
            )
            ->add('miscellaneousSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Planner',
                            'name' => 'parentWeeklyEmailSummaryIncludeBehaviour',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Planner',
                            'name' => 'parentWeeklyEmailSummaryIncludeMarkbook',
                            'entry_type' => ToggleType::class,
                        ],
                    ],
                    'panel' => 'Miscellaneous',
                ]
            )
            ->add('submit3', SubmitType::class,
                [
                    'label' => 'Submit',
                    'panel' => 'Miscellaneous',
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
                'data_class' => null,
                'translation_domain' => 'SchoolAdmin',
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
}
