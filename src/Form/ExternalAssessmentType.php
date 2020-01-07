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
 * Date: 6/01/2020
 * Time: 13:37
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\ReactFormType;
use App\Form\Type\ToggleType;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExternalAssessmentType
 * @package Kookaburra\SchoolAdmin\Form
 */
class ExternalAssessmentType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'Name',
                    'help' => 'Must be unique',
                    'translation_domain' => 'messages',
                    'panel' => 'Details',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Abbreviation',
                    'help' => 'Must be unique',
                    'translation_domain' => 'messages',
                    'panel' => 'Details',
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label' => 'Description',
                    'help' => 'Brief description of assessment and how it is used.',
                    'attr' => [
                        'rows' => '2',
                    ],
                    'panel' => 'Details',
                ]
            )
            ->add('active', ToggleType::class,
                [
                    'label' => 'Active',
                    'panel' => 'Details',
                ]
            )
            ->add('allowFileUpload', ToggleType::class,
                [
                    'panel' => 'Details',
                    'label' => 'Allow File Upload',
                    'help' => 'Should the student record include the option of a file upload?',
                ]
            )
            ->add('nothing', HiddenType::class,
                [
                    'panel' => 'Fields',
                    'mapped' => false,
                    'data' => 'nothing',
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Submit',
                    'panel' => 'Details',
                    'translation_domain' => 'messages',
                ]
            )
        ;
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
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'SchoolAdmin',
                'data_class' => ExternalAssessment::class,
            ]
        );
    }
}
