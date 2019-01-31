<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Address;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ShipmentGui\Business\ShipmentGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class AddressForm extends AbstractType
{
    public const ADDRESS_FIELD_SALUTATION = 'salutation';
    public const ADDRESS_FIELD_FIRST_NAME = 'first_name';
    public const ADDRESS_FIELD_MIDDLE_NAME = 'middle_name';
    public const ADDRESS_FIELD_LAST_NAME = 'last_name';
    public const ADDRESS_FIELD_EMAIL = 'email';
    public const ADDRESS_FIELD_ADDRESS_1 = 'address1';
    public const ADDRESS_FIELD_ADDRESS_2 = 'address2';
    public const ADDRESS_FIELD_COMPANY = 'company';
    public const ADDRESS_FIELD_CITY = 'city';
    public const ADDRESS_FIELD_ZIP_CODE = 'zip_code';
    public const ADDRESS_FIELD_PO_BOX = 'po_box';
    public const ADDRESS_FIELD_PHONE = 'phone';
    public const ADDRESS_FIELD_CELL_PHONE = 'cell_phone';
    public const ADDRESS_FIELD_DESCRIPTION = 'description';
    public const ADDRESS_FIELD_COMMENT = 'comment';
    public const ADDRESS_FIELD_ISO_2_CODE = 'iso2Code';

    public const OPTION_SALUTATION_CHOICES = 'salutation_choices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(AddressForm::OPTION_SALUTATION_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        parent::buildForm($builder, $options);

        $this
            ->addSalutationField($builder, $options[static::OPTION_SALUTATION_CHOICES])
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
            ->addCommentField($builder);
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
                'required' => false,
                'label' => 'Salutation',
                'placeholder' => '-select-',
                'choices' => array_flip($options),
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
    protected function addMiddleNameField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::ADDRESS_FIELD_MIDDLE_NAME, TextType::class, [
                'required' => false,
                'label' => 'Middle name',
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
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::ADDRESS_FIELD_EMAIL, TextType::class, [
                'required' => true,
                'label' => 'Email',
                'constraints' => [
                    new Email(),
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
    protected function addAddress1Field(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::ADDRESS_FIELD_ADDRESS_1, TextType::class, [
                'required' => true,
                'label' => 'Address 1',
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
                'required' => false,
                'label' => 'Addres 2',
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
            ]);

        return $this;
    }
}
