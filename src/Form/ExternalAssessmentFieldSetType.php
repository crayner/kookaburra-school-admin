<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 21/03/2020
 * Time: 08:39
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\ChoiceChainedType;
use App\Form\Type\DisplayType;
use App\Provider\ProviderFactory;
use Doctrine\ORM\EntityRepository;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessment;
use Kookaburra\SchoolAdmin\Entity\ExternalAssessmentField;
use Kookaburra\SchoolAdmin\Manager\Hidden\ExternalAssessmentByYearGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExternalAssessmentFieldSetType
 * @package Kookaburra\SchoolAdmin\Form
 */
class ExternalAssessmentFieldSetType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('yearGroupName', DisplayType::class,
                [
                    'label' => 'Year Groups',
                ]
            )
            ->add('yearGroup', HiddenType::class)
            ->add('externalAssessment', EntityType::class,
                [
                    'class' => ExternalAssessment::class,
                    'label' => 'External Assessments',
                    'placeholder' => ' ',
                    'choice_label' => 'name',
                    'chained_child' => 'fieldSet',
                    'chained_values' => $this->createFieldSetChoice(),
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('e')
                            ->orderBy('e.name', 'ASC')
                        ;
                    },
                ]
            )->add('fieldSet', ChoiceType::class,
                [
                    'label' => 'Field Set',
                    'placeholder' => ' ',
                    'choices' => [],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ExternalAssessmentByYearGroup::class,
                'translation_domain' => 'SchoolAdmin',
            ]
        );
    }

    /**
     * @var array|null
     */
    private $fieldSetChoices;

    /**
     * createFieldSetChoice
     * @return array
     */
    private function createFieldSetChoice(): array
    {
        if (is_array($this->fieldSetChoices))
            return $this->fieldSetChoices;

        $this->fieldSetChoices = ProviderFactory::create(ExternalAssessmentField::class)->findFieldSetChoices();

        return $this->fieldSetChoices;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $value = $view->vars['value'];

        $view->children['externalAssessment']->vars['value'] = $value->getExternalAssessment();
        $view->children['fieldSet']->vars['value'] = $value->getFieldSet();
    }


}