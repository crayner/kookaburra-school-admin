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
 * Date: 10/01/2020
 * Time: 09:11
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\ReactFormType;
use App\Form\Type\ToggleType;
use App\Util\TranslationsHelper;
use Doctrine\ORM\EntityRepository;
use Kookaburra\SchoolAdmin\Entity\Scale;
use Kookaburra\SchoolAdmin\Entity\ScaleGrade;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ScaleType
 * @package Kookaburra\SchoolAdmin\Form
 */
class ScaleType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $scale = $options['data'];
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
            ->add('usage', TextType::class,
                [
                    'label' => 'Usage',
                    'help' => 'Brief description of how scale is used.',
                ]
            )
            ->add('active', ToggleType::class,
                [
                    'label' => 'Active',
                ]
            )
            ->add('numeric', ToggleType::class,
                [
                    'label' => 'Numeric',
                    'help' => 'Does this scale use only numeric grades? Note, grade "Incomplete" is exempt.',
                ]
            )
        ;
        if ($scale->getId() > 0)
            $builder
                ->add('lowestAcceptable', EntityType::class,
                    [
                        'label' => 'Lowest Acceptable',
                        'help' => 'This is the lowest grade a student can get without being unsatisfactory.',
                        'class' => ScaleGrade::class,
                        'placeholder' => TranslationsHelper::translate('Please select...',[],'messages'),
                        'choice_translation_domain' => false,
                        'data' => $scale->getLowestAcceptable(),
                        'choice_label' => 'value',
                        'query_builder' => function(EntityRepository $er) use ($scale) {
                            return $er->createQueryBuilder('g')
                                ->where('g.scale = :scale')
                                ->setParameter('scale', $scale)
                                ->orderBy('g.sequenceNumber', 'ASC')
                            ;
                        },
                    ]
                );
        $builder
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Submit',
                    'translation_domain' => 'messages',
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
                'data_class' => Scale::class,
            ]
        );
    }
}