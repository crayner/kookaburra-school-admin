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
 * Date: 21/12/2019
 * Time: 16:20
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\EnumType;
use App\Form\Type\HeaderType;
use App\Form\Type\ReactDateType;
use App\Form\Type\ReactFormType;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AcademicYearType
 * @package Kookaburra\SchoolAdmin\Form
 */
class AcademicYearType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('yearHeader', HeaderType::class,
                [
                    'label' => intval($options['data']->getId()) > 0 ? 'Edit Academic Year' : 'Add Academic Year',
                ]
            )
            ->add('name', TextType::class,
                [
                    'label' => 'Name',
                    'help' => 'Must be unique',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('status', EnumType::class,
                [
                    'label' => 'Status',
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
                'data_class' => AcademicYear::class,
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