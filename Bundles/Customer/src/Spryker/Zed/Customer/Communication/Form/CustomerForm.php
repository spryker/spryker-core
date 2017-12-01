<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form;

use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CustomerForm extends AbstractType
{
    const OPTION_SALUTATION_CHOICES = 'salutation_choices';
    const OPTION_GENDER_CHOICES = 'gender_choices';

    const FIELD_EMAIL = 'email';
    const FIELD_SALUTATION = 'salutation';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_GENDER = 'gender';
    const FIELD_SEND_PASSWORD_TOKEN = 'send_password_token';
    const FIELD_ID_CUSTOMER = 'id_customer';

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     */
    public function __construct(CustomerQueryContainerInterface $customerQueryContainer)
    {
        $this->customerQueryContainer = $customerQueryContainer;
    }

    /**
     * @return string
     */
    public function getName()
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
            ->addSendPasswordField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCustomerField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_CUSTOMER, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_EMAIL, 'email', [
            'label' => 'Email',
            'constraints' => $this->createEmailConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return \Spryker\Zed\Customer\Communication\Form\CustomerForm
     */
    protected function addSalutationField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_SALUTATION, 'choice', [
            'label' => 'Salutation',
            'placeholder' => 'Select one',
            'choices' => $choices,
            'required' => false,
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
        $builder->add(self::FIELD_FIRST_NAME, 'text', [
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
        $builder->add(self::FIELD_LAST_NAME, 'text', [
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
        $builder->add(self::FIELD_GENDER, 'choice', [
            'label' => 'Gender',
            'placeholder' => 'Select one',
            'choices' => $choices,
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
        $builder->add(self::FIELD_SEND_PASSWORD_TOKEN, 'checkbox', [
            'label' => 'Send password token through email',
            'required' => false,
        ]);

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

        $customerQuery = $this->customerQueryContainer->queryCustomers();

        $emailConstraints[] = new Callback([
            'methods' => [
                function ($email, ExecutionContextInterface $context) use ($customerQuery) {
                    if ($customerQuery->findByEmail($email)->count() > 0) {
                        $context->addViolation('Email is already used');
                    }
                },
            ],
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
}
