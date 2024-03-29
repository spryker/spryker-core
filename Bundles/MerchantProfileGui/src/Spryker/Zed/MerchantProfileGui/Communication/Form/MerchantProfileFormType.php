<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Form;

use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantProfileGui\Communication\Form\MerchantProfileGlossary\MerchantProfileLocalizedGlossaryAttributesFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\MerchantProfileGui\MerchantProfileGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 */
class MerchantProfileFormType extends AbstractType
{
    /**
     * @var string
     */
    public const SALUTATION_CHOICES_OPTION = 'salutation_choices';

    /**
     * @var string
     */
    protected const FIELD_ID_MERCHANT_PROFILE = 'id_merchant_profile';

    /**
     * @var string
     */
    protected const FIELD_CONTACT_PERSON_ROLE = 'contact_person_role';

    /**
     * @var string
     */
    protected const FIELD_CONTACT_PERSON_TITLE = 'contact_person_title';

    /**
     * @var string
     */
    protected const FIELD_CONTACT_PERSON_FIRST_NAME = 'contact_person_first_name';

    /**
     * @var string
     */
    protected const FIELD_CONTACT_PERSON_LAST_NAME = 'contact_person_last_name';

    /**
     * @var string
     */
    protected const FIELD_CONTACT_PERSON_PHONE = 'contact_person_phone';

    /**
     * @var string
     */
    protected const FIELD_LOGO_URL = 'logo_url';

    /**
     * @var string
     */
    protected const FIELD_PUBLIC_EMAIL = 'public_email';

    /**
     * @var string
     */
    protected const FIELD_PUBLIC_PHONE = 'public_phone';

    /**
     * @var string
     */
    protected const FIELD_MERCHANT_PROFILE_LOCALIZED_GLOSSARY_ATTRIBUTES = 'merchantProfileLocalizedGlossaryAttributes';

    /**
     * @var string
     */
    protected const FIELD_DESCRIPTION_GLOSSARY_KEY = 'description_glossary_key';

    /**
     * @var string
     */
    protected const FIELD_BANNER_URL_GLOSSARY_KEY = 'banner_url_glossary_key';

    /**
     * @var string
     */
    protected const FIELD_DELIVERY_TIME_GLOSSARY_KEY = 'delivery_time_glossary_key';

    /**
     * @var string
     */
    protected const FIELD_TERMS_CONDITIONS_GLOSSARY_KEY = 'terms_conditions_glossary_key';

    /**
     * @var string
     */
    protected const FIELD_CANCELLATION_POLICY_GLOSSARY_KEY = 'cancellation_policy_glossary_key';

    /**
     * @var string
     */
    protected const FIELD_IMPRINT_GLOSSARY_KEY = 'imprint_glossary_key';

    /**
     * @var string
     */
    protected const FIELD_DATA_PRIVACY_GLOSSARY_KEY = 'data_privacy_glossary_key';

    /**
     * @var string
     */
    protected const FIELD_FAX_NUMBER = 'fax_number';

    /**
     * @var string
     */
    protected const LABEL_CONTACT_PERSON_ROLE = 'Role';

    /**
     * @var string
     */
    protected const LABEL_CONTACT_PERSON_TITLE = 'Title';

    /**
     * @var string
     */
    protected const LABEL_CONTACT_PERSON_FIRST_NAME = 'First Name';

    /**
     * @var string
     */
    protected const LABEL_CONTACT_PERSON_LAST_NAME = 'Last Name';

    /**
     * @var string
     */
    protected const LABEL_CONTACT_PERSON_PHONE = 'Phone';

    /**
     * @var string
     */
    protected const LABEL_LOGO_URL = 'Logo URL';

    /**
     * @var string
     */
    protected const LABEL_PUBLIC_EMAIL = 'Public Email';

    /**
     * @var string
     */
    protected const LABEL_PUBLIC_PHONE = 'Public Phone';

    /**
     * @var string
     */
    protected const LABEL_FAX_NUMBER = 'Fax number';

    /**
     * @var string
     */
    protected const URL_PATH_PATTERN = '#^([^\s\\\\]+)$#i';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => MerchantProfileTransfer::class,
        ]);

        $resolver->setRequired(static::SALUTATION_CHOICES_OPTION);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdMerchantProfileField($builder)
            ->addContactPersonPhoneField($builder)
            ->addContactPersonTitleField($builder, $options[static::SALUTATION_CHOICES_OPTION])
            ->addContactPersonFirstNameField($builder)
            ->addContactPersonLastNameField($builder)
            ->addContactPersonRoleField($builder)
            ->addPublicEmailField($builder)
            ->addPublicPhoneField($builder)
            ->addLogoUrlField($builder)
            ->addDescriptionGlossaryKeyField($builder)
            ->addBannerUrlGlossaryKeyField($builder)
            ->addDeliveryTimeGlossaryKeyField($builder)
            ->addTermsConditionsGlossaryKeyField($builder)
            ->addCancellationPolicyGlossaryKeyField($builder)
            ->addImprintGlossaryKeyField($builder)
            ->addDataPrivacyGlossaryKeyField($builder)
            ->addMerchantProfileLocalizedGlossaryAttributesSubform($builder)
            ->addFaxNumber($builder)
            ->addAddressCollectionSubform($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdMerchantProfileField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_MERCHANT_PROFILE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DESCRIPTION_GLOSSARY_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addBannerUrlGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_BANNER_URL_GLOSSARY_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDeliveryTimeGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DELIVERY_TIME_GLOSSARY_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addTermsConditionsGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_TERMS_CONDITIONS_GLOSSARY_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCancellationPolicyGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CANCELLATION_POLICY_GLOSSARY_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addImprintGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IMPRINT_GLOSSARY_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDataPrivacyGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DATA_PRIVACY_GLOSSARY_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addContactPersonTitleField(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add(static::FIELD_CONTACT_PERSON_TITLE, ChoiceType::class, [
            'choices' => array_flip($choices),
            'required' => false,
            'label' => static::LABEL_CONTACT_PERSON_TITLE,
            'placeholder' => 'Select one',

        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addContactPersonFirstNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_FIRST_NAME, TextType::class, [
            'label' => static::LABEL_CONTACT_PERSON_FIRST_NAME,
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addContactPersonLastNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_LAST_NAME, TextType::class, [
            'label' => static::LABEL_CONTACT_PERSON_LAST_NAME,
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addContactPersonRoleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_ROLE, TextType::class, [
            'label' => static::LABEL_CONTACT_PERSON_ROLE,
            'constraints' => $this->getTextFieldConstraints(),
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
        $builder->add(static::FIELD_PUBLIC_EMAIL, TextType::class, [
            'label' => static::LABEL_PUBLIC_EMAIL,
            'required' => false,
            'constraints' => [
                new Email(),
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
    protected function addPublicPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PUBLIC_PHONE, TextType::class, [
            'label' => static::LABEL_PUBLIC_PHONE,
            'constraints' => $this->getTextFieldConstraints(),
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addContactPersonPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_PHONE, TextType::class, [
            'label' => static::LABEL_CONTACT_PERSON_PHONE,
            'constraints' => $this->getTextFieldConstraints(),
            'required' => false,
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
        $merchantProfileAddressFormDataProvider = $this->getFactory()->createMerchantProfileAddressFormDataProvider();

        $builder->add(
            'addressCollection',
            MerchantProfileAddressFormType::class,
            $merchantProfileAddressFormDataProvider->getOptions(),
        );

        return $this;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
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
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getUrlFieldConstraints(array $choices = []): array
    {
        return [
            new Length(['max' => 255]),
            new Regex([
                'pattern' => static::URL_PATH_PATTERN,
                'message' => 'Invalid url provided. "Space" and "\" character is not allowed.',
            ]),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFaxNumber(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FAX_NUMBER, TextType::class, [
            'label' => static::LABEL_FAX_NUMBER,
            'required' => false,
            'constraints' => [new Length(['max' => 255])],
        ]);

        return $this;
    }
}
