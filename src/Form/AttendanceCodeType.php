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
 * Date: 17/01/2020
 * Time: 13:46
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\EntityType;
use App\Form\Type\EnumType;
use App\Form\Type\ReactFormType;
use App\Form\Type\ToggleType;
use App\Provider\ProviderFactory;
use Doctrine\ORM\EntityRepository;
use Kookaburra\SchoolAdmin\Entity\AttendanceCode;
use Kookaburra\SystemAdmin\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AttendanceCodeType
 * @package Kookaburra\SchoolAdmin\Form
 */
class AttendanceCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'Name',
                    'help' => 'Must be unique',
                ]
            )
            ->add('code', TextType::class,
                [
                    'label' => 'Code',
                    'help' => 'Must be unique',
                ]
            )
            ->add('direction', EnumType::class,
                [
                    'label' => 'Direction',
                ]
            )
            ->add('Scope', EnumType::class,
                [
                    'label' => 'Scope',
                ]
            )
            ->add('sequenceNumber', NumberType::class,
                [
                    'label' => 'Sequence Number',
                    'help' => 'Must be unique,  Use for sort.',
                ]
            )
            ->add('active', ToggleType::class,
                [
                    'label' => 'Active',
                ]
            )
            ->add('reportable', ToggleType::class,
                [
                    'label' => 'Reportable',
                ]
            )
            ->add('future', ToggleType::class,
                [
                    'label' => 'Allow Future Use',
                    'help' => 'Can this code be used in Set Future Absence?'
                ]
            )
            ->add('roleAll', EntityType::class,
                [
                    'label' => 'Available to Roles',
                    'help' => 'Controls who can use this code.<br/>
Use Control, Command and/or Shift to select multiple.',
                    'class' => Role::class,
                    'data' => $options['data']->getRoleAll(),
                    'choice_label' => 'name',
                    'multiple' => true,
                    'attr' => [
                        'style' => [
                            'height' => '125px',
                        ],
                    ],
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('r')
                            ->orderBy('r.name')
                        ;
                    },
                    'group_by' => function ($role) {
                        return $role->getCategory();
                    },
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
               'data_class' => AttendanceCode::class,
           ]
       );
    }
}