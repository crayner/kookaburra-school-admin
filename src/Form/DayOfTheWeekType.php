<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 22/12/2019
 * Time: 17:59
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\HeaderType;
use App\Form\Type\ReactFormType;
use App\Form\Type\ToggleType;
use Kookaburra\SchoolAdmin\Entity\DaysOfWeek;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DayOfTheWeekType
 * @package Kookaburra\SchoolAdmin\Form
 */
class DayOfTheWeekType extends AbstractType
{
    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'SchoolAdmin',
                'data_class' => DaysOfWeek::class,
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
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', HiddenType::class)
            ->add('nameShort', HiddenType::class)
            ->add('id', HiddenType::class)
            ->add('dayName', HeaderType::class,
                [
                    'label' => $options['data']->getName(),
                    'help' => $options['data']->getNameShort(),
                ]
            )
            ->add('schoolDay', ToggleType::class,
                [
                    'label' => 'School Day',
                    'visibleByClass' => 'is-school-day-' . $options['data']->getNameShort(),
                ]
            )
            ->add('schoolOpen', TimeType::class,
                [
                    'label' => 'School Opens',
                    'with_seconds' => false,
                    'input' => 'datetime_immutable',
                    'widget' => 'single_text',
                    'row_class' => 'flex flex-col sm:flex-row justify-between content-center p-0 is-school-day-' . $options['data']->getNameShort(),
                ]
            )
            ->add('schoolStart', TimeType::class,
                [
                    'label' => 'School Starts',
                    'with_seconds' => false,
                    'input' => 'datetime_immutable',
                    'widget' => 'single_text',
                    'row_class' => 'flex flex-col sm:flex-row justify-between content-center p-0 is-school-day-' . $options['data']->getNameShort(),
                ]
            )
            ->add('schoolEnd', TimeType::class,
                [
                    'label' => 'School Ends',
                    'with_seconds' => false,
                    'input' => 'datetime_immutable',
                    'widget' => 'single_text',
                    'row_class' => 'flex flex-col sm:flex-row justify-between content-center p-0 is-school-day-' . $options['data']->getNameShort(),
                ]
            )
            ->add('schoolClose', TimeType::class,
                [
                    'label' => 'School Closes',
                    'with_seconds' => false,
                    'input' => 'datetime_immutable',
                    'widget' => 'single_text',
                    'row_class' => 'flex flex-col sm:flex-row justify-between content-center p-0 is-school-day-' . $options['data']->getNameShort(),
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Submit'
                ]
            )
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['id'] = 'day_of_the_week_' . strtolower($options['data']->getName());
        $view->vars['name'] = $view->vars['id'];
        $view->vars['full_name'] = $view->vars['id'];
        $view->vars['unique_block_prefix'] = '_' . $view->vars['id'];
    }
}