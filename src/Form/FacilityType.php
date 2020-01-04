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
 * Time: 17:12
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\EnumType;
use App\Form\Type\ReactFormType;
use App\Form\Type\ToggleType;
use Kookaburra\SchoolAdmin\Entity\Facility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FacilityType
 * @package Kookaburra\SchoolAdmin\Form
 */
class FacilityType extends AbstractType
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
                ]
            )
            ->add('type', EnumType::class,
                [
                    'label' => 'Facility Type',
                    'placeholder' => 'Please select...',
                ]
            )
            ->add('capacity', NumberType::class,
                [
                    'label' => 'Capacity',
                    'required' => false,
                ]
            )
            ->add('computer', ToggleType::class,
                [
                    'label' => 'Teacher Computer',
                ]
            )
            ->add('studentComputers', NumberType::class,
                [
                    'label' => 'Student Computers',
                    'help' => 'How many are there',
                    'required' => false,
                ]
            )
            ->add('projector', ToggleType::class,
                [
                    'label' => 'Projector',
                ]
            )
            ->add('tv', ToggleType::class,
                [
                    'label' => 'TV',
                ]
            )
            ->add('dvd', ToggleType::class,
                [
                    'label' => 'DVD Player',
                ]
            )
            ->add('hifi', ToggleType::class,
                [
                    'label' => 'Hifi',
                ]
            )
            ->add('speakers', ToggleType::class,
                [
                    'label' => 'Speakers',
                ]
            )
            ->add('iwb', ToggleType::class,
                [
                    'label' => 'Interactive White Board',
                ]
            )
            ->add('phoneInt', TextType::class,
                [
                    'label' => 'Extension',
                    'required' => false,
                    'help' => 'Room\'s internal phone number.',
                ]
            )
            ->add('phoneExt', TextType::class,
                [
                    'label' => 'Phone Number',
                    'help' => 'Room\'s external phone number.',
                    'required' => false,
                ]
            )
            ->add('comment', TextareaType::class,
                [
                    'label' => 'Comment',
                    'required' => false,
                    'attr' => [
                        'rows' => 6,
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
                'data_class' => Facility::class,
            ]
        );
    }
}