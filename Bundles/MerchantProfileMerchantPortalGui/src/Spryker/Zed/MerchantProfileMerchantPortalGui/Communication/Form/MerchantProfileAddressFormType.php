<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Transformer\MerchantProfileAddressTransfersToMerchantProfileAddressTransferTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig getConfig()
 */
class MerchantProfileAddressFormType extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_CITY = 'city';

    /**
     * @var string
     */
    protected const FIELD_ZIP_CODE = 'zip_code';

    /**
     * @var string
     */
    protected const FIELD_FK_COUNTRY = 'fk_country';

    /**
     * @var string
     */
    protected const FIELD_ADDRESS_1 = 'address1';

    /**
     * @var string
     */
    protected const FIELD_ADDRESS_2 = 'address2';

    /**
     * @var string
     */
    protected const FIELD_ADDRESS_3 = 'address3';

    /**
     * @var string
     */
    protected const FIELD_LATITUDE = 'latitude';

    /**
     * @var string
     */
    protected const FIELD_LONGITUDE = 'longitude';

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
        $resolver->setDefaults([
            'data_class' => MerchantProfileAddressTransfer::class,
            'label' => false,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCountryField($builder)
            ->addAddress1Field($builder)
            ->addAddress2Field($builder)
            ->addZipCodeField($builder)
            ->addCityField($builder)
            ->addAddress3Field($builder)
            ->addLatitudeField($builder)
            ->addLongitudeField($builder);

        $builder->addModelTransformer(
            new MerchantProfileAddressTransfersToMerchantProfileAddressTransferTransformer(),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addCountryField(FormBuilderInterface $builder)
    {
        $merchantProfileAddressFormDataProvider = $this->getFactory()->createMerchantProfileAddressFormDataProvider();

        $builder->add(static::FIELD_FK_COUNTRY, ChoiceType::class, [
            'label' => static::LABEL_FK_COUNTRY,
            'placeholder' => 'select.default.placeholder',
            'required' => false,
            'choices' => array_flip($merchantProfileAddressFormDataProvider->getCountryChoices()),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
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
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
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
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
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
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addAddress2Field(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_2, TextType::class, [
            'label' => static::LABEL_ADDRESS_2,
            'required' => false,
            'constraints' => [new Length(['max' => 255])],
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @return $this
     */
    protected function addAddress3Field(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_3, TextType::class, [
            'label' => static::LABEL_ADDRESS_3,
            'required' => false,
            'constraints' => [new Length(['max' => 255])],
            'sanitize_xss' => true,
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
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
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
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
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
