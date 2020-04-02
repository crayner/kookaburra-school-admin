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
 * Date: 4/01/2020
 * Time: 14:20
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\HeaderType;
use App\Form\Type\ReactFormType;
use Kookaburra\SystemAdmin\Form\SettingsType;
use App\Form\Type\SimpleArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FacilitySettingsType
 * @package Kookaburra\SchoolAdmin\Form
 */
class FacilitySettingsType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('securitySettingsHeader', HeaderType::class,
                [
                    'label' => 'Facility Settings'
                ]
            )
            ->add('facilitySettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'School Admin',
                            'name' => 'facilityTypes',
                            'entry_type' => SimpleArrayType::class,
                            'entry_options' => [
                            ],
                        ],
                    ],
                ]
            )
            ->add('submit', SubmitType::class)
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
}
