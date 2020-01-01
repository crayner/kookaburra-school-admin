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
 * Date: 31/12/2019
 * Time: 18:31
 */

namespace Kookaburra\SchoolAdmin\Form;

use App\Form\Type\FilePathType;
use App\Form\Type\ReactFileType;
use App\Form\Type\ReactFormType;
use App\Validator\ReactFile;
use App\Validator\ReactImage;
use Kookaburra\SchoolAdmin\Entity\House;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HouseType
 * @package Kookaburra\SchoolAdmin\Form
 */
class HouseType extends AbstractType
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
            ->add('logo', ReactFileType::class,
                [
                    'label' => 'Logo',
                    'help' => 'Image file size maximum 2MB',
                    'file_prefix' => 'house_logo_',
                    'data' => $options['data']->getLogo(),
                    'constraints' => [
                         new ReactImage([
                             'maxSize' => "2M",
                             'minRatio' => 0.7,
                             'maxRatio' => 1,
                             'mimeTypes' => "image/*"
                         ]),
                    ],
                ]
            )
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
                'data_class' => House::class,
            ]
        );
    }
}