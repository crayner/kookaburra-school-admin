<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 23/12/2019
 * Time: 12:44
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Transform\DateStringTransform;
use App\Form\Type\DisplayType;
use App\Form\Type\EntityType;
use App\Form\Type\EnumType;
use App\Form\Type\HeaderType;
use App\Form\Type\ReactDateType;
use App\Form\Type\ReactFormType;
use Doctrine\ORM\EntityRepository;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Kookaburra\SchoolAdmin\Entity\AcademicYearSpecialDay;
use Kookaburra\SchoolAdmin\Form\Transform\AcademicYearNameTransform;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SpecialDayType
 * @package Kookaburra\SchoolAdmin\Form
 */
class SpecialDayType extends AbstractType
{
    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return ReactFormType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'SchoolAdmin',
            'data_class' => AcademicYearSpecialDay::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $day = $options['data'];
        $builder
            ->add('dayHeader', HeaderType::class,
                [
                    'label' => $day->getId() > 0 ? 'Edit Special Day' : 'Add Special Day'  ,
                ]
            )
        ;
        if ($day->getId() !== null) {
            $builder
                ->add('academicYear', DisplayType::class,
                    [
                        'label' => 'Academic Year',
                        'help' => 'This value is locked.'
                    ]
                )
                ->add('date', DisplayType::class,
                    [
                        'label' => 'Date',
                    ]
                )
            ;
            $builder->get('date')->addViewTransformer(new DateStringTransform(true));
        } else {
            $builder
                ->add('academicYear', DisplayType::class,
                    [
                        'label' => 'Academic Year',
                        'help' => 'This value is locked. Change it in the manage screen.'
                    ]
                )
                ->add('date', ReactDateType::class,
                    [
                        'label' => 'Date',
                        'help' => 'Must be unique in the Academic Year.',
                        'input' => 'datetime_immutable',
                    ]
                )
            ;
        }
            $builder
                ->add('type', EnumType::class,
                    [
                        'label' => 'Type',
                        'placeholder' => 'Please select...',
                        'visibleByClass' => 'timingChange',
                        'visibleWhen' => 'Timing Change',
                        'values' => AcademicYearSpecialDay::getTypeList(),
                    ]
                )
                ->add('name', TextType::class,
                    [
                        'label' => 'Name',
                    ]
                )
                ->add('description', TextType::class,
                    [
                        'label' => 'Description',
                        'required' => false,
                    ]
                )
                ->add('schoolOpen', TimeType::class,
                    [
                        'label' => 'School Opens',
                        'required' => false,
                        'input' => 'datetime_immutable',
                        'widget' => 'single_text',
                        'row_class' => 'timingChange flex flex-col sm:flex-row justify-between content-center p-0',
                    ]
                )
                ->add('schoolStart', TimeType::class,
                    [
                        'label' => 'School Starts',
                        'required' => false,
                        'input' => 'datetime_immutable',
                        'widget' => 'single_text',
                        'row_class' => 'timingChange flex flex-col sm:flex-row justify-between content-center p-0',
                    ]
                )
                ->add('schoolEnd', TimeType::class,
                    [
                        'label' => 'School Ends',
                        'required' => false,
                        'input' => 'datetime_immutable',
                        'row_class' => 'timingChange flex flex-col sm:flex-row justify-between content-center p-0',
                        'widget' => 'single_text',
                    ]
                )
                ->add('schoolClose', TimeType::class,
                    [
                        'label' => 'School Closes',
                        'required' => false,
                        'input' => 'datetime_immutable',
                        'row_class' => 'timingChange flex flex-col sm:flex-row justify-between content-center p-0',
                        'widget' => 'single_text',
                    ]
                )
                ->add('submit', SubmitType::class,
                    [
                        'label' => 'Submit',
                        'translation_domain' => 'messages',
                    ]
                )
            ;
        $builder->get('academicYear')->addViewTransformer(new AcademicYearNameTransform());
    }
}