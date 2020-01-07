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
 * Time: 16:04
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Entity\Scale;
use App\Form\Type\DisplayType;
use App\Form\Type\ReactFormType;
use App\Form\Type\SimpleArrayToEntityType;
use Doctrine\ORM\EntityRepository;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExternalAssessmentFieldType
 * @package Kookaburra\SchoolAdmin\Form
 */
class ExternalAssessmentFieldType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('externalAssessment', DisplayType::class,
                [
                    'label' => 'External Assessment',
                    'help' => 'This value cannot be changed.',
                    'data' => $options['data']->getExternalAssessment()->getName(),
                    'mapped' => false,
                ]
            )
            ->add('name', TextType::class,
                [
                    'label' => 'Name',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('category', TextType::class,
                [
                    'label' => 'Category',
                ]
            )
            ->add('order', TextType::class,
                [
                    'label' => 'Order',
                    'help' => 'Order in which fields appear within category. Should be unique for this category.'
                ]
            )
            ->add('scale', EntityType::class,
                [
                    'label' => 'Grade Scale',
                    'help' => 'Grade scale used to control values that can be assigned.',
                    'class' => Scale::class,
                    'placeholder' => 'Please select...',
                    'choice_label' => 'name',
                    'choice_translation_domain' => false,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->orderBy('s.name', 'ASC')
                            ->where('s.active = :true')
                            ->setParameter('true', 'Y')
                        ;
                    },
                ]
            )
            ->add('yearGroupList', SimpleArrayToEntityType::class,
                [
                    'label' => 'Year Groups',
                    'help' => 'Year groups to which this field is relevant.',
                    'multiple' => true,
                    'expanded' => true,
                    'class' => YearGroup::class,
                    'choice_label' => 'name',
                    'choice_translation_domain' => false,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->orderBy('s.nameShort', 'ASC')
                            ;
                    },
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
                'data_class' => ExternalAssessmentField::class,
            ]
        );
    }
}