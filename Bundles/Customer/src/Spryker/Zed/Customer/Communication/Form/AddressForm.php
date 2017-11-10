<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class AddressForm extends AbstractType
{
    const OPTION_SALUTATION_CHOICES = 'salutation_choices';
    const OPTION_COUNTRY_CHOICES = 'country_choices';
    const OPTION_PREFERRED_COUNTRY_CHOICES = 'preferred_country_choices';

    const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';
    const FIELD_FK_CUSTOMER = 'fk_customer';
    const FIELD_SALUTATION = 'salutation';
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_ADDRESS_1 = 'address1';
    const FIELD_ADDRESS_2 = 'address2';
    const FIELD_ADDRESS_3 = 'address3';
    const FIELD_CITY = 'city';
    const FIELD_ZIP_CODE = 'zip_code';
    const FIELD_FK_COUNTRY = 'fk_country';
    const FIELD_PHONE = 'phone';
    const FIELD_COMPANY = 'company';
    const FIELD_COMMENT = 'comment';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_SALUTATION_CHOICES);
        $resolver->setRequired(static::OPTION_COUNTRY_CHOICES);
        $resolver->setDefined(static::OPTION_PREFERRED_COUNTRY_CHOICES);

        $resolver->setDefaults([
            'required' => false,
            static::OPTION_PREFERRED_COUNTRY_CHOICES => [],
        ]);
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
            ->addIdCustomerAddressField($builder)
            ->addFkCustomerField($builder)
            ->addSalutationField($builder, $options[static::OPTION_SALUTATION_CHOICES])
            ->addFirstNameField($builder)
            ->addLastNameField($builder)
            ->addAddress1Field($builder)
            ->addAddress2Field($builder)
            ->addAddress3Field($builder)
            ->addCityField($builder)
            ->addZipCodeField($builder)
            ->addFkCountryField($builder, $options[static::OPTION_COUNTRY_CHOICES], $options[static::OPTION_PREFERRED_COUNTRY_CHOICES])
            ->addPhoneField($builder)
            ->addCompanyField($builder)
            ->addCommentField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCustomerAddressField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_CUSTOMER_ADDRESS, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkCustomerField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_CUSTOMER, 'hidden');

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return \Spryker\Zed\Customer\Communication\Form\AddressForm
     */
    protected function addSalutationField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_SALUTATION, 'choice', [
            'label' => 'Salutation',
            'placeholder' => 'Select one',
            'choices' => $choices,
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
        $builder->add(static::FIELD_FIRST_NAME, 'text', [
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
        $builder->add(static::FIELD_LAST_NAME, 'text', [
            'label' => 'Last Name',
            'constraints' => $this->getTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddress1Field(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_1, 'text', [
            'label' => 'Address line 1',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddress2Field(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_2, 'text', [
            'label' => 'Address line 2',
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddress3Field(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_3, 'text', [
            'label' => 'Address line 3',
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCityField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CITY, 'text', [
            'label' => 'City',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addZipCodeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ZIP_CODE, 'text', [
            'label' => 'Zip Code',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 15]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     * @param array $preferredChoices
     *
     * @return \Spryker\Zed\Customer\Communication\Form\AddressForm
     */
    protected function addFkCountryField(FormBuilderInterface $builder, array $choices, array $preferredChoices = [])
    {
        $builder->add(static::FIELD_FK_COUNTRY, 'choice', [
            'label' => 'Country',
            'placeholder' => 'Select one',
            'choices' => $choices,
            'preferred_choices' => $preferredChoices,
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
    protected function addPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PHONE, 'text', [
            'label' => 'Phone',
            'constraints' => [
                new Length(['max' => 255]),
            ],
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
        $builder->add(static::FIELD_COMPANY, 'text', [
            'label' => 'Company',
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCommentField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_COMMENT, 'textarea', [
            'label' => 'Comment',
            'constraints' => [
                new Length(['max' => 255]),
            ],
        ]);

        return $this;
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
     * @return string
     */
    public function getName()
    {
        return 'customer_address';
    }
}
