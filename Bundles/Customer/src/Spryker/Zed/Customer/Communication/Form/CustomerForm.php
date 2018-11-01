<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form;

use DateTime;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class CustomerForm extends AbstractType
{
    public const OPTION_SALUTATION_CHOICES = 'salutation_choices';
    public const OPTION_GENDER_CHOICES = 'gender_choices';
    public const OPTION_LOCALE_CHOICES = 'locale_choices';

    public const FIELD_EMAIL = 'email';
    public const FIELD_SALUTATION = 'salutation';
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_GENDER = 'gender';
    public const FIELD_SEND_PASSWORD_TOKEN = 'send_password_token';
    public const FIELD_ID_CUSTOMER = 'id_customer';
    public const FIELD_COMPANY = 'company';
    public const FIELD_PHONE = 'phone';
    public const FIELD_DATE_OF_BIRTH = 'date_of_birth';
    public const FIELD_LOCALE = 'locale';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'customer';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_SALUTATION_CHOICES);
        $resolver->setRequired(self::OPTION_GENDER_CHOICES);
        $resolver->setRequired(self::OPTION_LOCALE_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdCustomerField($builder)
            ->addEmailField($builder)
            ->addSalutationField($builder, $options[self::OPTION_SALUTATION_CHOICES])
            ->addFirstNameField($builder)
            ->addLastNameField($builder)
            ->addGenderField($builder, $options[self::OPTION_GENDER_CHOICES])
            ->addDateOfBirthField($builder)
            ->addPhoneField($builder)
            ->addCompanyField($builder)
            ->addLocaleField($builder, $options[static::OPTION_LOCALE_CHOICES])
            ->addSendPasswordField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCustomerField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_CUSTOMER, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_EMAIL, EmailType::class, [
            'label' => 'Email',
            'constraints' => $this->createEmailConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addSalutationField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_SALUTATION, ChoiceType::class, [
            'label' => 'Salutation',
            'placeholder' => 'Select one',
            'choices' => array_flip($choices),
            'choices_as_values' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFirstNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_FIRST_NAME, TextType::class, [
            'label' => 'First Name',
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLastNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_LAST_NAME, TextType::class, [
            'label' => 'Last Name',
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addGenderField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_GENDER, ChoiceType::class, [
            'label' => 'Gender',
            'placeholder' => 'Select one',
            'choices' => array_flip($choices),
            'choices_as_values' => true,
            'constraints' => [
                new Required(),
            ],
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSendPasswordField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_SEND_PASSWORD_TOKEN, CheckboxType::class, [
            'label' => 'Send password token through email',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder)
    {
        $companyConstraints = [
            new Length(['max' => 100]),
        ];

        $builder->add(static::FIELD_COMPANY, TextType::class, [
            'label' => 'Company',
            'required' => false,
            'constraints' => $companyConstraints,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPhoneField(FormBuilderInterface $builder)
    {
        $phoneConstraints = [
            new Length(['max' => 255]),
        ];

        $builder->add(static::FIELD_PHONE, TextType::class, [
            'label' => 'Phone',
            'required' => false,
            'constraints' => $phoneConstraints,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addLocaleField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_LOCALE, ChoiceType::class, [
            'label' => 'Locale',
            'placeholder' => 'Select one',
            'choices' => array_flip($choices),
            'choices_as_values' => true,
            'required' => false,

        ]);

        $builder->get(static::FIELD_LOCALE)
            ->addModelTransformer($this->createLocaleModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDateOfBirthField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DATE_OF_BIRTH, DateType::class, [
            'label' => 'Date of birth',
            'widget' => 'single_text',
            'required' => false,
            'attr' => [
                'class' => 'datepicker safe-datetime',
            ],
        ]);

        $builder->get(static::FIELD_DATE_OF_BIRTH)
            ->addModelTransformer($this->createDateTimeModelTransformer());

        return $this;
    }

    /**
     * @return array
     */
    protected function createEmailConstraints()
    {
        $emailConstraints = [
            new NotBlank(),
            new Required(),
            new Email(),
        ];

        $customerQuery = $this->getQueryContainer()->queryCustomers();

        $emailConstraints[] = new Callback([
            'callback' => function ($email, ExecutionContextInterface $context) use ($customerQuery) {
                if ($customerQuery->findByEmail($email)->count() > 0) {
                    $context->addViolation('Email is already used');
                }
            },
        ]);

        return $emailConstraints;
    }

    /**
     * @return array
     */
    protected function getTextFieldConstraints()
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 100]),
        ];
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer()
    {
        return new CallbackTransformer(
            function ($dateAsString) {
                if ($dateAsString !== null) {
                    return new DateTime($dateAsString);
                }
            },
            function ($dateAsObject) {
                return $dateAsObject;
            }
        );
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createLocaleModelTransformer()
    {
        return new CallbackTransformer(
            function ($localeAsObject) {
                if ($localeAsObject !== null) {
                    return $localeAsObject->getIdLocale();
                }
            },
            function ($localeAsInt) {
                if ($localeAsInt !== null) {
                    return $this->getFactory()->getLocaleFacadePublic()->getLocaleById($localeAsInt);
                }
            }
        );
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
