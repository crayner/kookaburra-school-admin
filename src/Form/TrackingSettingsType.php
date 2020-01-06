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
 * Time: 08:57
 */

namespace Kookaburra\SchoolAdmin\Form;

use Kookaburra\SchoolAdmin\Manager\Hidden\TrackingSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TrackingSettingsType
 * @package Kookaburra\SchoolAdmin\Form
 */
class TrackingSettingsType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('external', CollectionType::class,
                [
                    'allow_add' => false,
                    'allow_delete' => false,
                    'required' => false,
                    'entry_type' => AssessmentFieldType::class,
                ]
            )
            ->add('internal', CollectionType::class,
                [
                    'allow_add' => false,
                    'allow_delete' => false,
                    'required' => false,
                    'entry_type' => AssessmentFieldType::class,
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
                'translation_domain' => 'SchoolAdmin',
                'data_class' => TrackingSettings::class,
                'attr' => [
                    'class' => 'smallIntBorder fullWidth',
                ],
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
