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
 * Date: 14/01/2020
 * Time: 15:29
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\DisplayType;
use App\Form\Type\HiddenEntityType;
use App\Form\Type\ReactFormType;
use App\Form\Type\ToggleType;
use Kookaburra\SchoolAdmin\Entity\Scale;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ScaleGradeType
 * @package Kookaburra\SchoolAdmin\Form
 */
class ScaleGradeType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('scaleGrade', DisplayType::class,
                [
                    'label' => 'Scale',
                    'mapped' => false,
                    'help' => 'This value cannot be changed.',
                    'data' => $options['data']->getScale()->getName(),
                ]
            )
            ->add('scale', HiddenEntityType::class,
                [
                    'class' => Scale::class,
                ]
            )
            ->add('value', TextType::class,
                [
                    'label' => 'Value',
                    'help' => 'Must be unique for this Scale',
                ]
            )
            ->add('descriptor', TextType::class,
                [
                    'label' => 'Descriptor',
                ]
            )
            ->add('isDefault', ToggleType::class,
                [
                    'label' => 'Is Default?',
                    'help' => 'Preselects this option when using this scale in appropriate contexts.',
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
                'data_class' => ScaleGrade::class,
            ]
        );
    }
}