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
 * Time: 17:25
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\EnumType;
use App\Form\Type\HeaderType;
use App\Form\Type\ReactFormType;
use Kookaburra\SchoolAdmin\Entity\FileExtension;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FileExtensionType
 * @package Kookaburra\SchoolAdmin\Form
 */
class FileExtensionType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('header', HeaderType::class,
                [
                    'label' => 'File Extension',
                ]
            )
            ->add('extension', TextType::class,
                [
                    'label' => 'Extension',
                    'help' => 'Must be Unique',
                ]
            )
            ->add('name', TextType::class,
                [
                    'label' => 'Name',
                ]
            )
            ->add('type', EnumType::class,
                [
                    'label' => 'Type',
                    'placeholder' => 'Please select...',
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
                'data_class' => FileExtension::class,
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