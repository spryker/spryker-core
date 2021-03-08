<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\MerchantProfileGlossary\MerchantProfileLocalizedGlossaryAttributesFormType;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\MerchantProfileUrlCollection\MerchantProfileUrlCollectionFormType;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Form\Transformer\MerchantProfileUrlCollectionDataTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\MerchantProfileMerchantPortalGuiCommunicationFactory getFactory()
 */
class OnlineProfileMerchantProfileForm extends AbstractType
{
    protected const FIELD_LOGO_URL = 'logo_url';
    protected const FIELD_PUBLIC_EMAIL = 'public_email';
    protected const FIELD_PUBLIC_PHONE = 'public_phone';
    protected const FIELD_MERCHANT_PROFILE_LOCALIZED_GLOSSARY_ATTRIBUTES = 'merchantProfileLocalizedGlossaryAttributes';
    protected const FIELD_ADDRESS_COLLECTION = 'addressCollection';
    protected const FIELD_IS_ACTIVE = 'is_active';
    protected const FIELD_URL_COLLECTION = 'urlCollection';
    protected const FIELD_LATITUDE = 'latitude';
    protected const FIELD_LONGITUDE = 'longitude';
    protected const FIELD_FAX_NUMBER = 'fax_number';

    protected const LABEL_LOGO_URL = 'Logo URL';
    protected const LABEL_PUBLIC_EMAIL = 'Email';
    protected const LABEL_PUBLIC_PHONE = 'Phone Number';
    protected const LABEL_IS_ACTIVE = 'Is Active';
    protected const LABEL_LATITUDE = 'Latitude';
    protected const LABEL_LONGITUDE = 'Longitude';
    protected const LABEL_FAX_NUMBER = 'Fax number';

    protected const PLACEHOLDER_LOGO_URL = 'Provide a logo URL';

    protected const URL_PATH_PATTERN = '#^([^\s\\\\]+)$#i';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addPublicEmailField($builder)
            ->addPublicPhoneField($builder)
            ->addLogoUrlField($builder)
            ->addIsActiveField($builder)
            ->addUrlCollectionField($builder)
            ->addMerchantProfileLocalizedGlossaryAttributesSubform($builder)
            ->addFaxNumberField($builder)
//            ->addLatitudeField($builder)
//            ->addLongitudeField($builder)
            ->addAddressCollectionSubform($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUrlCollectionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_URL_COLLECTION, CollectionType::class, [
            'entry_type' => MerchantProfileUrlCollectionFormType::class,
            'allow_add' => true,
            'label' => false,
            'required' => true,
            'allow_delete' => true,
            'entry_options' => [
                'label' => false,
                'data_class' => UrlTransfer::class,
            ],
        ]);

        $builder->get(static::FIELD_URL_COLLECTION)
            ->addModelTransformer(new MerchantProfileUrlCollectionDataTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_IS_ACTIVE, CheckboxType::class, [
                'label' => static::LABEL_IS_ACTIVE,
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPublicEmailField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PUBLIC_EMAIL, EmailType::class, [
            'label' => static::LABEL_PUBLIC_EMAIL,
            'required' => false,
            'constraints' => [
                new Email(),
                new Length(['max' => 255]),
            ],
            'property_path' => 'merchantProfile.publicEmail',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPublicPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PUBLIC_PHONE, TextType::class, [
            'label' => static::LABEL_PUBLIC_PHONE,
            'constraints' => $this->getTextFieldConstraints(),
            'required' => false,
            'property_path' => 'merchantProfile.publicPhone',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLogoUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_LOGO_URL, TextType::class, [
            'label' => static::LABEL_LOGO_URL,
            'required' => false,
            'constraints' => $this->getUrlFieldConstraints(),
            'attr' => [
                'placeholder' => static::PLACEHOLDER_LOGO_URL,
            ],
            'property_path' => 'merchantProfile.logoUrl',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantProfileLocalizedGlossaryAttributesSubform(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_MERCHANT_PROFILE_LOCALIZED_GLOSSARY_ATTRIBUTES, CollectionType::class, [
            'label' => false,
            'entry_type' => MerchantProfileLocalizedGlossaryAttributesFormType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'property_path' => 'merchantProfile.merchantProfileLocalizedGlossaryAttributes',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddressCollectionSubform(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ADDRESS_COLLECTION, MerchantProfileAddressFormType::class, [
            'property_path' => 'merchantProfile.addressCollection',
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getRequiredTextFieldConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @param array $choices
     *
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getUrlFieldConstraints(array $choices = []): array
    {
        return [
            new Length(['max' => 255]),
            new Regex([
                'pattern' => static::URL_PATH_PATTERN,
                'message' => 'Invalid URL provided. "Space" and "\" character is not allowed.',
            ]),
        ];
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
                new Length([
                    'max' => 255,
                ]),
                new Regex([
                    'pattern' => '/^(\\+|-)?(?:180(?:(?:\\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\\.[0-9]{1,6})?))$/',
                ]),
            ],
            'property_path' => 'merchantProfile.longitude',
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
                new Length([
                    'max' => 255,
                ]),
                new Regex([
                    'pattern' => '/^(\\+|-)?(?:90(?:(?:\\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\\.[0-9]{1,6})?))$/',
                ]),
            ],
            'property_path' => 'merchantProfile.latitude',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFaxNumberField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FAX_NUMBER, TextType::class, [
            'label' => static::LABEL_FAX_NUMBER,
            'required' => false,
            'constraints' => [
                new Length([
                    'max' => 255,
                ]),
            ],
            'property_path' => 'merchantProfile.faxNumber',
        ]);

        return $this;
    }
}
