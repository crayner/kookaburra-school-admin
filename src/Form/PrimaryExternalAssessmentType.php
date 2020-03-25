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
 * Date: 9/01/2020
 * Time: 13:18
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\HeaderType;
use App\Form\Type\ReactCollectionType;
use App\Form\Type\ReactFormType;
use Kookaburra\SchoolAdmin\Manager\Hidden\ExternalAssessmentByYearGroups;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PrimaryExternalAssessmentType
 * @package Kookaburra\SchoolAdmin\Form
 */
class PrimaryExternalAssessmentType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('primaryExternalHeader', HeaderType::class,
                [
                    'label' => 'Primary External Assessment',
                    'help' => 'primary_external_help',
                ]
            )
            ->add('yearGroups', ReactCollectionType::class,
                [
                    'element_delete_route' => false,
                    'entry_type' => ExternalAssessmentFieldSetType::class,
                    'row_style' => 'single',
                    'header_row' => [
                        [
                            'label' => 'Year Groups',
                        ],
                        [
                            'label' => 'External Assessments',
                        ],
                        [
                            'label' => 'Field Sets',
                        ],
                    ],
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Submit',
                    'translation_domain' => 'SchoolAdmin',
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
                'data_class' => ExternalAssessmentByYearGroups::class,
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