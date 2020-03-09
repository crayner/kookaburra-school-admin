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

use App\Form\Type\HeaderType;
use App\Form\Type\ReactCollectionType;
use App\Form\Type\ReactFormType;
use Kookaburra\SchoolAdmin\Form\Subscriber\TrackingSettingsSubscriber;
use Kookaburra\SchoolAdmin\Manager\Hidden\DataPoints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExternalDataPointsType
 * @package Kookaburra\SchoolAdmin\Form
 */
class ExternalDataPointsType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ext-header', HeaderType::class,
                [
                    'label' =>         'Data Point - External Assessment',
                    'help' => 'external_assessment_help',
                ]
            )
            ->add('dataPoints', ReactCollectionType::class,
                [
                    'allow_add' => false,
                    'allow_delete' => false,
                    'required' => false,
                    'entry_type' => AssessmentFieldType::class,
                    'row_style' => 'transparent',
                    'element_delete_route' => false,
                ]
            )
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Submit',
                    'translation_domain' => 'messages',
                ]
            )
            ->addEventSubscriber(new TrackingSettingsSubscriber('external'))
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
                'data_class' => DataPoints::class,
                'attr' => [
                    'className' => 'smallIntBorder fullWidth',
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
        return ReactFormType::class;
    }
}
