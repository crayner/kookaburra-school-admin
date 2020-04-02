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
 * Date: 15/01/2020
 * Time: 11:26
 */

namespace Kookaburra\SchoolAdmin\Form;

use Kookaburra\SystemAdmin\Entity\Setting;
use App\Form\Type\EnumType;
use App\Form\Type\HeaderType;
use App\Form\Type\ReactFormType;
use Kookaburra\SystemAdmin\Form\SettingsType;
use App\Form\Type\SimpleArrayType;
use App\Form\Type\ToggleType;
use App\Provider\ProviderFactory;
use Doctrine\ORM\EntityRepository;
use Kookaburra\UserAdmin\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

/**
 * Class FinanceSettingsType
 * @package Kookaburra\SchoolAdmin\Form
 */
class FinanceSettingsType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('generalHeader', HeaderType::class,
                [
                    'label' => 'General Settings',
                    'panel' => 'General',
                ]
            )
            ->add('generalSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Finance',
                            'name' => 'email',
                            'entry_type' => EmailType::class,
                            'entry_options' => [
                                'constraints' => [
                                    new Email(),
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'financeOnlinePaymentEnabled',
                            'entry_type' => ToggleType::class,
                            'entry_options' => [
                                'visibleByClass' => 'financeOnlinePaymentEnabled',
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'financeOnlinePaymentThreshold',
                            'entry_type' => TextType::class,
                            'entry_options' => [
                                'row_class' => 'financeOnlinePaymentEnabled flex flex-col sm:flex-row justify-between content-center p-0',
                            ],
                        ],
                    ],
                    'panel' => 'General',
                ]
            )
            ->add('submit1', SubmitType::class,
                [
                    'label' => 'Submit',
                    'panel' => 'General',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('invoicesHeader', HeaderType::class,
                [
                    'label' => 'Invoices',
                    'panel' => 'Invoices',
                ]
            )
            ->add('invoicesSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Finance',
                            'name' => 'invoiceText',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'invoiceNotes',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'invoiceeNameStyle',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_class' => FinanceSettingsType::class,
                                'choice_list_method' => 'getNameStyleList',
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'invoiceNumber',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_class' => FinanceSettingsType::class,
                                'choice_list_method' => 'getInvoiceNumberList',
                            ],
                        ],
                    ],
                    'panel' => 'Invoices',
                ]
            )
            ->add('submit2', SubmitType::class,
                [
                    'label' => 'Submit',
                    'panel' => 'Invoices',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('receiptHeader', HeaderType::class,
                [
                    'label' => 'Receipts',
                    'panel' => 'Receipts',
                ]
            )
            ->add('receiptSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Finance',
                            'name' => 'receiptText',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'receiptNotes',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'hideItemisation',
                            'entry_type' => ToggleType::class,
                        ],
                    ],
                    'panel' => 'Receipts',
                ]
            )
            ->add('submit3', SubmitType::class,
                [
                    'label' => 'Submit',
                    'panel' => 'Receipts',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('reminderHeader', HeaderType::class,
                [
                    'label' => 'Reminders',
                    'panel' => 'Reminders',
                ]
            )
            ->add('reminderSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Finance',
                            'name' => 'reminder1Text',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'reminder2Text',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'reminder3Text',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                    ],
                    'panel' => 'Reminders',
                ]
            )
            ->add('submit4', SubmitType::class,
                [
                    'label' => 'Submit',
                    'panel' => 'Reminders',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('expenseHeader', HeaderType::class,
                [
                    'label' => 'Expenses',
                    'panel' => 'Expenses',
                ]
            )
            ->add('expenseSettings', SettingsType::class,
                [
                    'settings' => [
                        [
                            'scope' => 'Finance',
                            'name' => 'budgetCategories',
                            'entry_type' => SimpleArrayType::class,
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'expenseApprovalType',
                            'entry_type' => EnumType::class,
                            'entry_options' => [
                                'choice_list_class' => FinanceSettingsType::class,
                                'choice_list_method' => 'getApprovalList',
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'budgetLevelExpenseApproval',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'expenseRequestTemplate',
                            'entry_type' => TextareaType::class,
                            'entry_options' => [
                                'attr' => [
                                    'rows' => 6,
                                ],
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'allowExpenseAdd',
                            'entry_type' => ToggleType::class,
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'purchasingOfficer',
                            'entry_type' => EntityType::class,
                            'entry_options' => [
                                'class' => Person::class,
                                'choice_label' => 'fullNameReversed',
                                'placeholder' => ' ',
                                'choice_translation_domain' => false,
                                'query_builder' => function(EntityRepository $er){
                                    return $er->createQueryBuilder('p')
                                        ->select(['p','s'])
                                        ->join('p.staff', 's')
                                        ->where('p.status = :full')
                                        ->andWhere('s.id IS NOT NULL')
                                        ->setParameter('full', 'Full')
                                        ->orderBy('p.surname')
                                        ->addOrderBy('p.firstName')
                                        ;
                                },
                            ],
                        ],
                        [
                            'scope' => 'Finance',
                            'name' => 'reimbursementOfficer',
                            'entry_type' => EntityType::class,
                            'entry_options' => [
                                'class' => Person::class,
                                'choice_label' => 'fullNameReversed',
                                'choice_translation_domain' => false,
                                'placeholder' => ' ',
                                'query_builder' => function(EntityRepository $er){
                                    return $er->createQueryBuilder('p')
                                        ->select(['p','s'])
                                        ->join('p.staff', 's')
                                        ->where('p.status = :full')
                                        ->andWhere('s.id IS NOT NULL')
                                        ->setParameter('full', 'Full')
                                        ->orderBy('p.surname')
                                        ->addOrderBy('p.firstName')
                                        ;
                                },
                            ],
                        ],
                    ],
                    'panel' => 'Expenses',
                ]
            )
            ->add('submit5', SubmitType::class,
                [
                    'label' => 'Submit',
                    'panel' => 'Expenses',
                    'translation_domain' => 'messages',
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
                'data_class' => null,
                'translation_domain' => 'SchoolAdmin',
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

    /**
     * getNameStyleList
     * @return array
     */
    public static function getNameStyleList(): array
    {
        return [
            'Surname, Preferred Name',
            'Official Name',
        ];
    }

    /**
     * getInvoiceNumberList
     * @return array
     */
    public static function getInvoiceNumberList(): array
    {
        return [
            'Invoice ID',
            'Person ID + Invoice ID',
            'Student ID + Invoice ID',
        ];
    }

    /**
     * getApprovalList
     * @return array
     */
    public static function getApprovalList(): array
    {
        return [
            'One Of','Two Of','Chain Of All'
        ];
    }
}