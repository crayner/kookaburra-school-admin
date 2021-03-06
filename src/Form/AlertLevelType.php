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
 * Date: 16/01/2020
 * Time: 13:50
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\HeaderType;
use App\Form\Type\ReactFormType;
use Kookaburra\SchoolAdmin\Entity\AlertLevel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AlertLevelType
 * @package Kookaburra\SchoolAdmin\Form
 */
class AlertLevelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('header', HeaderType::class,
                [
                    'label' => $options['data']->getName(),
                ]
            )
            ->add('name', TextType::class,
                [
                    'label' => 'Name',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Abbreviation',
                ]
            )
            ->add('colour', ColorType::class,
                [
                    'label' => 'Font/Border Colour',
                    'help' => 'RGB Hex Value',
                ]
            )
            ->add('colourBG', ColorType::class,
                [
                    'label' => 'Background Colour',
                    'help' => 'RGB Hex Value',
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label' => 'Background Colour',
                    'required' => false,
                    'attr' => [
                        'rows' => 5,
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
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'SchoolAdmin',
                'data_class' => AlertLevel::class,
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

    /**
     * buildView
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['id'] = 'alert_level_' . $options['data']->getId();
        $view->vars['name'] = 'alert_level_' . $options['data']->getId();
        $view->vars['full_name'] = 'alert_level_' . $options['data']->getId();
        $view->vars['id'] = 'alert_level_' . $options['data']->getId();
    }
}