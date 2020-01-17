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
 * Date: 18/01/2020
 * Time: 08:43
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\ReactFormType;
use Kookaburra\SchoolAdmin\Entity\INDescriptor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class IndividualNeedsType
 * @package Kookaburra\SchoolAdmin\Form
 */
class INDescriptorType extends AbstractType
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
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Code',
                    'help' => 'Must be unique',
                ]
            )
            ->add('sequenceNumber', NumberType::class,
                [
                    'label' => 'Sequence Number',
                    'help' => 'Must be unique',
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label' => 'Description',
                    'required' => false,
                    'attr' => [
                        'rows' => 7,
                    ],
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Submit',
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
                'data_class' => INDescriptor::class,
            ]
        );
    }
}