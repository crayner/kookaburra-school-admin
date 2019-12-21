<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 21/12/2019
 * Time: 20:07
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\HeaderType;
use App\Form\Type\ReactDateType;
use App\Form\Type\ReactFormType;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Entity\AcademicYearTerm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AcademicYearTermType
 * @package Kookaburra\SchoolAdmin\Form
 */
class AcademicYearTermType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('termHeader', HeaderType::class,
                [
                    'label' => intval($options['data']->getId()) > 0 ? 'Edit Term' : 'Add Term',
                ]
            )
            ->add('academicYear', EntityType::class,
                [
                    'label' => 'Academic Year',
                    'placeholder' => 'Please select...',
                    'class' => AcademicYear::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('name', TextType::class,
                [
                    'label' => 'Name',
                    'help' => 'Must be unique in the Academic Year',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Abbreviation',
                    'help' => 'Must be unique in the Academic Year',
                ]
            )
            ->add('sequenceNumber', NumberType::class,
                [
                    'label' => 'Sequence Number',
                    'help' => 'Must be unique. Helps to control chronological ordering.'
                ]
            )
            ->add('firstDay', ReactDateType::class,
                [
                    'label' => 'First Day',
                    'input' => 'datetime_immutable'
                ]
            )
            ->add('lastDay', ReactDateType::class,
                [
                    'label' => 'Last Day',
                    'input' => 'datetime_immutable'
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Submit',
                    'translation_domain' => 'messages'
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
                'data_class' => AcademicYearTerm::class,
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