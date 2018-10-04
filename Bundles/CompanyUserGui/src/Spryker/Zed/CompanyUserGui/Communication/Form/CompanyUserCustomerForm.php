<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Form;

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
 * @see \Spryker\Zed\Customer\Communication\CustomerForm original customer form
 *
 * @method \Spryker\Zed\CompanyUserGui\Communication\CompanyUserGuiCommunicationFactory getFactory()
 */
class CompanyUserCustomerForm extends AbstractType
{
    public const OPTION_SALUTATION_CHOICES = 'salutation_choices';
    public const OPTION_GENDER_CHOICES = 'gender_choices';

    protected const FIELD_EMAIL = 'email';
    protected const FIELD_SALUTATION = 'salutation';
    protected const FIELD_FIRST_NAME = 'first_name';
    protected const FIELD_LAST_NAME = 'last_name';
    protected const FIELD_GENDER = 'gender';
    protected const FIELD_SEND_PASSWORD_TOKEN = 'send_password_token';
    protected const FIELD_ID_CUSTOMER = 'id_customer';
    protected const FIELD_COMPANY = 'company';
    protected const FIELD_PHONE = 'phone';
    protected const FIELD_DATE_OF_BIRTH = 'date_of_birth';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'customer';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(static::OPTION_SALUTATION_CHOICES);
        $resolver->setDefined(static::OPTION_GENDER_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdCustomerField($builder)
            ->addEmailField($builder)
            ->addSalutationField($builder, $options[static::OPTION_SALUTATION_CHOICES])
            ->addFirstNameField($builder)
            ->addLastNameField($builder)
            ->addGenderField($builder, $options[static::OPTION_GENDER_CHOICES])
            ->addSendPasswordField($builder)
            ->addDateOfBirthField($builder)
            ->addPhoneField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCustomerField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_CUSTOMER, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_EMAIL, EmailType::class, [
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
    protected function addSalutationField(FormBuilderInterface $builder, array $choices): self
    {
        $builder->add(static::FIELD_SALUTATION, ChoiceType::class, [
            'label' => 'Salutation',
            'placeholder' => 'Select one',
            'choices' => $choices,
            'choices_as_values' => true,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFirstNameField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_FIRST_NAME, TextType::class, [
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
    protected function addLastNameField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_LAST_NAME, TextType::class, [
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
    protected function addGenderField(FormBuilderInterface $builder, array $choices): self
    {
        $builder->add(static::FIELD_GENDER, ChoiceType::class, [
            'label' => 'Gender',
            'placeholder' => 'Select one',
            'choices' => $choices,
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
    protected function addSendPasswordField(FormBuilderInterface $builder): self
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
    protected function addPhoneField(FormBuilderInterface $builder): self
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
     *
     * @return $this
     */
    protected function addDateOfBirthField(FormBuilderInterface $builder): self
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
    protected function createEmailConstraints(): array
    {
        $emailConstraints = [
            new NotBlank(),
            new Required(),
            new Email(),
        ];

        $customerFacade = $this->getFactory()->getCustomerFacade();

        $emailConstraints[] = new Callback([
            'callback' => function ($email, ExecutionContextInterface $context) use ($customerFacade) {
                if ($customerFacade->hasEmail($email)) {
                    $context->addViolation('Email is already used');
                }
            },
        ]);

        return $emailConstraints;
    }

    /**
     * @return array
     */
    protected function getTextFieldConstraints(): array
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
    protected function createDateTimeModelTransformer(): CallbackTransformer
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
}
