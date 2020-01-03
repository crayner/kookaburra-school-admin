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
 * Date: 3/01/2020
 * Time: 12:34
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\ReactFormType;
use Doctrine\ORM\EntityRepository;
use Kookaburra\SchoolAdmin\Entity\YearGroup;
use Kookaburra\UserAdmin\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class YearGroupType
 * @package Kookaburra\SchoolAdmin\Form
 */
class YearGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'Name',
                    'help' => 'Must be unique',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Abbreviation',
                    'help' => 'Must be unique',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('sequenceNumber', NumberType::class,
                [
                    'label' => 'Sequence Number',
                    'help' => 'Must be unique. Controls chronological ordering',
                ]
            )
            ->add('headOfYear', EntityType::class,
                [
                    'label' => 'Head of Year',
                    'required' => false,
                    'class' => Person::class,
                    'choice_label' => 'fullNameReversed',
                    'placeholder' => ' ',
                    'choice_translation_domain' => false,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->leftJoin('p.staff', 's')
                            ->select(['p','s'])
                            ->where('s.id IS NOT NULL')
                            ->orderBy('p.surname')
                            ->addOrderBy('p.firstName')
                            ;
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
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'SchoolAdmin',
                'data_class' => YearGroup::class,
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