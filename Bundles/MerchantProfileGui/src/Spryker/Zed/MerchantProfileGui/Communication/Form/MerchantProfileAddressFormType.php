<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantProfileGui\Communication\Form\Transformer\MerchantProfileAddressTransfersToMerchantProfileAddressTransferTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 */
class MerchantProfileAddressFormType extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_COUNTRY_CHOICES = 'country_choices';

    /**
     * @var string
     */
    public const FIELD_ID_MERCHANT_PROFILE_ADDRESS = 'id_merchant_profile_address';

    /**
     * @var string
     */
    public const FIELD_CITY = 'city';

    /**
     * @var string
     */
    public const FIELD_ZIP_CODE = 'zip_code';

    /**
     * @var string
     */
    public const FIELD_FK_COUNTRY = 'fk_country';

    /**
     * @var string
     */
    public const FIELD_ADDRESS_1 = 'address1';

    /**
     * @var string
     */
    public const FIELD_ADDRESS_2 = 'address2';

    /**
     * @var string
     */
    public const FIELD_ADDRESS_3 = 'address3';

    /**
     * @var string
     */
    public const FIELD_LATITUDE = 'latitude';

    /**
     * @var string
     */
    public const FIELD_LONGITUDE = 'longitude';

    /**
     * @var string
     */
    protected const LABEL_CITY = 'City';

    /**
     * @var string
     */
    protected const LABEL_ZIP_CODE = 'Zip Code';

    /**
     * @var string
     */
    protected const LABEL_FK_COUNTRY = 'Country';

    /**
     * @var string
     */
    protected const LABEL_ADDRESS_1 = 'Street';

    /**
     * @var string
     */
    protected const LABEL_ADDRESS_2 = 'Number';

    /**
     * @var string
     */
    protected const LABEL_ADDRESS_3 = 'Addition to address';

    /**
     * @var string
     */
    protected const LABEL_LATITUDE = 'Latitude';

    /**
     * @var string
     */
    protected const LABEL_LONGITUDE = 'Longitude';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_COUNTRY_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdMerchantProfileAddressField($builder)
            ->addCountryField($builder, $options[static::OPTION_COUNTRY_CHOICES])
            ->addAddress1Field($builder)
            ->addAddress2Field($builder)
            ->addZipCodeField($builder)
            ->addCityField($builder)
            ->addAddress3Field($builder)
            ->addLatitudeField($builder)
            ->addLongitudeField($builder);

        $builder->addModelTransformer(new MerchantProfileAddressTransfersToMerchantProfileAddressTransferTransformer());
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdMerchantProfileAddressField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_MERCHANT_PROFILE_ADDRESS, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addCountryField(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add(static::FIELD_FK_COUNTRY, ChoiceType::class, [
            'label' => static::LABEL_FK_COUNTRY,
            'placeholder' => 'Select one',
            'required' => false,
            'choices' => array_flip($choices),
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
        $builder->add(static::FIELD_CITY, TextType::class, [
            'label' => static::LABEL_CITY,
            'required' => false,
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
    protected function addZipCodeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ZIP_CODE, TextType::class, [
            'label' => static::LABEL_ZIP_CODE,
            'required' => false,
            'constraints' => [
                new Length(['max' => 10]),
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
        $builder->add(static::FIELD_ADDRESS_1, TextType::class, [
            'label' => static::LABEL_ADDRESS_1,
            'required' => false,
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
    protected function addAddress2Field(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_2, TextType::class, [
            'label' => static::LABEL_ADDRESS_2,
            'required' => false,
            'constraints' => [new Length(['max' => 255])],
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
        $builder->add(static::FIELD_ADDRESS_3, TextType::class, [
            'label' => static::LABEL_ADDRESS_3,
            'required' => false,
            'constraints' => [new Length(['max' => 255])],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'merchant_profile_address';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLongitudeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LONGITUDE, TextType::class, [
            'label' => static::LABEL_LONGITUDE,
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
                new Regex(['pattern' => '/^(\\+|-)?(?:180(?:(?:\\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\\.[0-9]{1,6})?))$/']),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLatitudeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LATITUDE, TextType::class, [
            'label' => static::LABEL_LATITUDE,
            'required' => false,
            'constraints' => [
                new Length(['max' => 255]),
                new Regex(['pattern' => '/^(\\+|-)?(?:90(?:(?:\\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\\.[0-9]{1,6})?))$/']),
            ],
        ]);

        return $this;
    }
}
