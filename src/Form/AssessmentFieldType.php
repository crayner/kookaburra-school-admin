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
 * Date: 5/01/2020
 * Time: 15:50
 */

namespace Kookaburra\SchoolAdmin\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AssessmentFieldType
 * @package Kookaburra\SchoolAdmin\Form
 */
class AssessmentFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('yearGroupList', ChoiceType::class,
                [
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
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
        return FormType::class;
    }
}