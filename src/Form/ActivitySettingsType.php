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
 * Time: 14:00
 */

namespace Kookaburra\SchoolAdmin\Form;


use App\Form\Type\EnumType;
use App\Form\Type\HeaderType;
use App\Form\Type\ParagraphType;
use App\Form\Type\ReactFormType;
use Kookaburra\SystemAdmin\Form\SettingsType;
use App\Form\Type\SimpleArrayType;
use App\Form\Type\ToggleType;
use App\Util\UrlGeneratorHelper;
use App\Validator\Colour;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivitySettingsType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('activityHeader', HeaderType::class,
                [
                    'label' => 'Activity Settings',
                ]
            )
            ->add('activitySettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Activities',
                            'name' => 'dateType',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_method' => 'getDateTypeList',
                                'choice_list_class' => ActivitySettingsType::class,
                            ],
                        ],
                        [
                            'scope' => 'Activities',
                            'name' => 'maxPerTerm',
                            'entry_type' => NumberType::class,
                            'entry_options' => [
                                'attr' => [
                                    'max' => 5,
                                    'min' => 0,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Activities',
                            'name' => 'access',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_method' => 'getAccessList',
                                'choice_list_class' => ActivitySettingsType::class,
                            ],
                        ],
                        [
                            'scope' => 'Activities',
                            'name' => 'payment',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_method' => 'getPaymentList',
                                'choice_list_class' => ActivitySettingsType::class,
                            ],
                        ],
                        [
                            'scope' => 'Activities',
                            'name' => 'enrolmentType',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_method' => 'getEnrolmentTypeList',
                                'choice_list_class' => ActivitySettingsType::class,
                            ],
                        ],
                        [
                            'scope' => 'Activities',
                            'name' => 'backupChoice',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Activities',
                            'name' => 'activityTypes',
                            'entry_type' => SimpleArrayType::class,
                        ],
                        [
                            'scope' => 'Activities',
                            'name' => 'disableExternalProviderSignup',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Activities',
                            'name' => 'hideExternalProviderCost',
                            'entry_type' => ToggleType::class,
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

    /**
     * getDateTypeList
     * @return array
     */
    public static function getDateTypeList(): array
    {
        return [
            'Date',
            'Term',
        ];
    }

    /**
     * getAccessList
     * @return array
     */
    public static function getAccessList(): array
    {
        return [
            'None',
            'View',
            'Register',
        ];
    }

    /**
     * getPaymentList
     * @return array
     */
    public static function getPaymentList(): array
    {
        return [
            'None',
            'Single',
            'Per Activity',
            'Single + Per Activity',
        ];
    }

    /**
     * getEnrolmentTypeList
     * @return array
     */
    public static function getEnrolmentTypeList(): array
    {
        return [
            'Competitive',
            'Selection',
        ];
    }
}