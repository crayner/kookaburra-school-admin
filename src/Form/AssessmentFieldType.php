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

use App\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('assessment', HiddenType::class)
            ->add('category', HiddenType::class)
            ->add('yearGroupList', EntityType::class,
                [
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'Nothing to see here',
                    'choice_label' => 'name',
                    'translation_domain' => false,
                    'choice_translation_domain' => false,
                    'class' => YearGroup::class,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                            ->orderBy('g.sequenceNumber', 'ASC')
                        ;
                    },
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
                'row_style' => 'transparent',
            ]
        );
    }
}