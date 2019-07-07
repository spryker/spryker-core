<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ShipmentGui\Communication\Form\Shipment\ShipmentFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class AddressFormType extends AbstractType
{
    public const OPTION_SHIPMENT_ADDRESS_CHOICES = 'address_choices';
    public const FIELD_ID_CUSTOMER_ADDRESS = 'idCustomerAddress';
    public const FIELD_ADDRESS_ID_SALES_ORDER_ADDRESS = 'idSalesOrderAddress';
    public const FIELD_ADDRESS_SALUTATION = 'salutation';
    public const FIELD_ADDRESS_FIRST_NAME = 'firstName';
    public const FIELD_ADDRESS_MIDDLE_NAME = 'middleName';
    public const FIELD_ADDRESS_LAST_NAME = 'lastName';
    public const FIELD_ADDRESS_EMAIL = 'email';
    public const FIELD_ADDRESS_ADDRESS_1 = 'address1';
    public const FIELD_ADDRESS_ADDRESS_2 = 'address2';
    public const FIELD_ADDRESS_COMPANY = 'company';
    public const FIELD_ADDRESS_CITY = 'city';
    public const FIELD_ADDRESS_ZIP_CODE = 'zipCode';
    public const FIELD_ADDRESS_PHONE = 'phone';
    public const FIELD_ADDRESS_CELL_PHONE = 'cellPhone';
    public const FIELD_ADDRESS_DESCRIPTION = 'description';
    public const FIELD_ADDRESS_COMMENT = 'comment';
    public const FIELD_ADDRESS_ISO_2_CODE = 'iso2Code';
    public const OPTION_SALUTATION_CHOICES = 'salutation_choices';

    public const VALUE_ADD_NEW_ADDRESS = '';
    public const ADDRESS_CHOICE_NEW_ADDRESS_LABEL = 'New address';

    public const ERROR_MESSAGE_VALUE_SHOULD_NOT_BE_BLANK = 'This value should not be blank.';
    protected const GROUP_SHIPPING_ADDRESS = 'shippingAddress';

    protected const VALIDATION_ZIP_CODE_PATTERN = '/^\d{5}$/';
    protected const VALIDATION_ZIP_CODE_MESSAGE = 'Zip code is not valid.';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::OPTION_SALUTATION_CHOICES)
            ->setRequired(static::OPTION_SHIPMENT_ADDRESS_CHOICES)
            ->setDefaults([
                'data_class' => AddressTransfer::class,
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
            ->add(static::FIELD_ADDRESS_SALUTATION, ChoiceType::class, [
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
            ->add(static::FIELD_ADDRESS_FIRST_NAME, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_MIDDLE_NAME, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_LAST_NAME, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_EMAIL, EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'constraints' => [
                    $this->createMaxLengthConstraint(),
                    $this->createEmailConstraint(),
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
            ->add(static::FIELD_ADDRESS_ISO_2_CODE, CountryType::class, [
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
            ->add(static::FIELD_ADDRESS_ADDRESS_1, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_ADDRESS_2, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_COMPANY, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_CITY, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_ZIP_CODE, TextType::class, [
                'required' => true,
                'label' => 'ZIP code',
                'constraints' => [
                    $this->createNotBlankConstraint(),
                    $this->createMaxLengthConstraint(15),
                    $this->createZipCodeConstraint(),
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
            ->add(static::FIELD_ADDRESS_PHONE, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_CELL_PHONE, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_DESCRIPTION, TextType::class, [
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
            ->add(static::FIELD_ADDRESS_COMMENT, TextareaType::class, [
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
            'groups' => [ShipmentFormType::VALIDATION_GROUP_SHIPPING_ADDRESS],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdSalesOrderAddressField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_ID_SALES_ORDER_ADDRESS, HiddenType::class);

        $builder->get(static::FIELD_ADDRESS_ID_SALES_ORDER_ADDRESS)
            ->addModelTransformer($this->getFactory()->createStringToNumberTransformer());

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
            'choices' => $options[static::OPTION_SHIPMENT_ADDRESS_CHOICES],
            'required' => false,
            'placeholder' => false,
        ]);

        $builder->get(static::FIELD_ID_CUSTOMER_ADDRESS)
            ->addModelTransformer($this->getFactory()->createStringToNumberTransformer());

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
            'groups' => [static::GROUP_SHIPPING_ADDRESS],
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Email
     */
    protected function createEmailConstraint(): Email
    {
        return new Email([
            'groups' => [static::GROUP_SHIPPING_ADDRESS],
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    protected function createZipCodeConstraint(): Regex
    {
        return new Regex([
            'pattern' => static::VALIDATION_ZIP_CODE_PATTERN,
            'message' => static::VALIDATION_ZIP_CODE_MESSAGE,
            'groups' => [static::GROUP_SHIPPING_ADDRESS],
        ]);
    }
}
