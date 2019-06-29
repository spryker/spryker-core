<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Address;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class AddressFormType extends AbstractType
{
    public const SHIPPING_ADDRESS_FIELDS = 'shipping_address';
    public const OPTION_SHIPMENT_ADDRESS_CHOICES = 'address_choices';
    public const FIELD_ID_CUSTOMER_ADDRESS = 'idCustomerAddress';
    public const ADDRESS_FIELD_ID_SALES_ORDER_ADDRESS = 'idSalesOrderAddress';
    public const ADDRESS_FIELD_SALUTATION = 'salutation';
    public const ADDRESS_FIELD_FIRST_NAME = 'firstName';
    public const ADDRESS_FIELD_MIDDLE_NAME = 'middleName';
    public const ADDRESS_FIELD_LAST_NAME = 'lastName';
    public const ADDRESS_FIELD_EMAIL = 'email';
    public const ADDRESS_FIELD_ADDRESS_1 = 'address1';
    public const ADDRESS_FIELD_ADDRESS_2 = 'address2';
    public const ADDRESS_FIELD_COMPANY = 'company';
    public const ADDRESS_FIELD_CITY = 'city';
    public const ADDRESS_FIELD_ZIP_CODE = 'zipCode';
    public const ADDRESS_FIELD_PO_BOX = 'poPox';
    public const ADDRESS_FIELD_PHONE = 'phone';
    public const ADDRESS_FIELD_CELL_PHONE = 'cellPhone';
    public const ADDRESS_FIELD_DESCRIPTION = 'description';
    public const ADDRESS_FIELD_COMMENT = 'comment';
    public const ADDRESS_FIELD_ISO_2_CODE = 'iso2Code';
    public const OPTION_SALUTATION_CHOICES = 'salutation_choices';

    public const ERROR_MESSAGE_VALUE_SHOULD_NOT_BE_BLANK = 'This value should not be blank.';
    protected const GROUP_SHIPPING_ADDRESS = 'shippingAddress';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(static::OPTION_SALUTATION_CHOICES)
            ->setRequired(static::OPTION_SHIPMENT_ADDRESS_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        $this
            ->addSalutationField($builder, $options)
            ->addFirstNameField($builder)
            ->addMiddleNameField($builder)
            ->addLastNameField($builder)
            ->addEmailField($builder)
            ->addCountryField($builder)
            ->addAddress1Field($builder)
            ->addAddress2Field($builder)
            ->addCompanyField($builder)
            ->addCityField($builder)
            ->addZipCodeField($builder)
            ->addPoBoxField($builder)
            ->addPhoneField($builder)
            ->addCellPhoneField($builder)
            ->addDescriptionField($builder)
            ->addCommentField($builder)
            ->addIdSalesOrderAddressField($builder)
            ->addIdShippingAddressField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSalutationField(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add(static::ADDRESS_FIELD_SALUTATION, ChoiceType::class, [
                'required' => true,
                'label' => 'Salutation',
                'placeholder' => '-select-',
                'choices' => $options[static::OPTION_SALUTATION_CHOICES],
                'constraints' => [
                    $this->createNotBlankConstraint(),
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
        $builder
            ->add(static::ADDRESS_FIELD_FIRST_NAME, TextType::class, [
                'required' => true,
                'label' => 'First name',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createMaxLengthConstraint(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMiddleNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::ADDRESS_FIELD_MIDDLE_NAME, TextType::class, [
                'required' => false,
                'label' => 'Middle name',
                'constraints' => [
                    $this->createMaxLengthConstraint(),
                ],
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
        $builder
            ->add(static::ADDRESS_FIELD_LAST_NAME, TextType::class, [
                'required' => true,
                'label' => 'Last name',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createMaxLengthConstraint(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::ADDRESS_FIELD_EMAIL, TextType::class, [
                'required' => true,
                'label' => 'Email',
                'constraints' => [
                    new Email(),
                    $this->createMaxLengthConstraint(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCountryField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::ADDRESS_FIELD_ISO_2_CODE, CountryType::class, [
                'required' => true,
                'label' => 'Country',
                'placeholder' => '-select-',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
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
        $builder
            ->add(static::ADDRESS_FIELD_ADDRESS_1, TextType::class, [
                'required' => true,
                'label' => 'Address 1',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createMaxLengthConstraint(),
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
        $builder
            ->add(static::ADDRESS_FIELD_ADDRESS_2, TextType::class, [
                'required' => true,
                'label' => 'Addres 2',
                'constraints' => [
                    $this->createMaxLengthConstraint(),
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
        $builder
            ->add(static::ADDRESS_FIELD_COMPANY, TextType::class, [
                'required' => false,
                'label' => 'Company',
                'constraints' => [
                    $this->createMaxLengthConstraint(),
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
        $builder
            ->add(static::ADDRESS_FIELD_CITY, TextType::class, [
                'required' => true,
                'label' => 'City',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createMaxLengthConstraint(),
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
        $builder
            ->add(static::ADDRESS_FIELD_ZIP_CODE, TextType::class, [
                'required' => true,
                'label' => 'ZIP code',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createMaxLengthConstraint(15),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPoBoxField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::ADDRESS_FIELD_PO_BOX, TextType::class, [
                'required' => false,
                'label' => 'PO box',
                'constraints' => [
                    $this->createMaxLengthConstraint(),
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
        $builder
            ->add(static::ADDRESS_FIELD_PHONE, TextType::class, [
                'required' => false,
                'label' => 'Phone',
                'constraints' => [
                    $this->createMaxLengthConstraint(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCellPhoneField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::ADDRESS_FIELD_CELL_PHONE, TextType::class, [
                'required' => false,
                'label' => 'Cellphone',
                'constraints' => [
                    $this->createMaxLengthConstraint(),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::ADDRESS_FIELD_DESCRIPTION, TextType::class, [
                'required' => false,
                'label' => 'Description',
                'constraints' => [
                    $this->createMaxLengthConstraint(),
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
        $builder
            ->add(static::ADDRESS_FIELD_COMMENT, TextareaType::class, [
                'required' => false,
                'label' => 'Comment',
                'constraints' => [
                    $this->createMaxLengthConstraint(),
                ],
            ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createNotBlankConstraint(): Constraint
    {
        return new NotBlank([
            'message' => 'Field should not be empty.',
            'groups' => [ShipmentFormType::GROUP_SHIPPING_ADDRESS],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdSalesOrderAddressField(FormBuilderInterface $builder)
    {
        $builder->add(static::ADDRESS_FIELD_ID_SALES_ORDER_ADDRESS, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIdShippingAddressField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_ID_CUSTOMER_ADDRESS, ChoiceType::class, [
            'label' => 'Delivery Address',
            'choices' => array_flip($options[static::OPTION_SHIPMENT_ADDRESS_CHOICES]),
            'required' => false,
            'placeholder' => false,
        ]);

        return $this;
    }

    /**
     * @param int $maxLength
     *
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createMaxLengthConstraint(int $maxLength = 255): Constraint
    {
        return new Length([
            'max' => $maxLength,
        ]);
    }
}
