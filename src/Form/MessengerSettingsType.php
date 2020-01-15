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
 * Time: 10:10
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\EnumType;
use App\Form\Type\HeaderType;
use App\Form\Type\ParagraphType;
use App\Form\Type\ReactFormType;
use App\Form\Type\SettingsType;
use App\Form\Type\SimpleArrayType;
use App\Form\Type\ToggleType;
use App\Util\UrlGeneratorHelper;
use App\Validator\Colour;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MessengerSettingsType
 * @package Kookaburra\SchoolAdmin\Form
 */
class MessengerSettingsType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('smsHeader', HeaderType::class,
                [
                    'label' => 'SMS Settings',
                ]
            )
            ->add('smsWarning', ParagraphType::class,
                [
                    'help' => 'messenger_sms_warning',
                    'help_translation_parameters' => [
                        '{link}' => '<a href="'.UrlGeneratorHelper::getPath('system_admin__third_party', ['tabName' => 'SMS']).'" target="_blank">',
                        '{linkClose}' => '</a>',
                    ],
                    'wrapper_class' => 'warning',
                ]
            )
            ->add('wallHeader', HeaderType::class,
                [
                    'label' => 'Message Wall Settings',
                ]
            )
            ->add('settings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Messenger',
                            'name' => 'messageBubbleWidthType',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_method' => 'getBubbleWidthList',
                                'choice_list_class' => MessengerSettingsType::class,
                            ],
                        ],
                        [
                            'scope' => 'Messenger',
                            'name' => 'messageBubbleBGColor',
                            'entry_type' => ColorType::class,
                            'entry_options' => [
                                'constraints' => [
                                    new Colour(['enforceType' => 'rgba']),
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Messenger',
                            'name' => 'messageBubbleAutoHide',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Messenger',
                            'name' => 'enableHomeScreenWidget',
                            'entry_type' => ToggleType::class,
                        ],
                    ],
                ]
            )
            ->add('miscHeader', HeaderType::class,
                [
                    'label' => 'Miscellaneous',
                ]
            )
            ->add('miscSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Messenger',
                            'name' => 'messageBcc',
                            'entry_type' => SimpleArrayType::class,
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
     * getBubbleWidthList
     * @return array
     */
    public static function getBubbleWidthList(): array
    {
        return [
            'Regular',
            'Wide',
        ];
    }
}