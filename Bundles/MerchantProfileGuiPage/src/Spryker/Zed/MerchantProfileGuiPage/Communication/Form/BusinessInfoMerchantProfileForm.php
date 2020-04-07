<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint\UniqueEmail;
use Spryker\Zed\MerchantProfileGuiPage\Communication\Form\Constraint\UniqueMerchantReference;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\MerchantProfileGuiPage\MerchantProfileGuiPageConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileGuiPage\Communication\MerchantProfileGuiPageCommunicationFactory getFactory()
 */
class BusinessInfoMerchantProfileForm extends AbstractType
{
    protected const FIELD_ID_MERCHANT = 'id_merchant';
    protected const FIELD_CONTACT_PERSON_TITLE = 'contact_person_title';
    protected const FIELD_CONTACT_PERSON_FIRST_NAME = 'contact_person_first_name';
    protected const FIELD_CONTACT_PERSON_LAST_NAME = 'contact_person_last_name';
    protected const FIELD_CONTACT_PERSON_ROLE = 'contact_person_role';
    protected const FIELD_NAME = 'name';
    protected const FIELD_REGISTRATION_NUMBER = 'registration_number';
    protected const FIELD_EMAIL = 'email';
    protected const FIELD_MERCHANT_REFERENCE = 'merchant_reference';
    protected const FIELD_MERCHANT_PROFILE = 'merchantProfile';
    protected const FIELD_CONTACT_PERSON_PHONE = 'contact_person_phone';

    protected const LABEL_CONTACT_PERSON_TITLE = 'Title';
    protected const LABEL_CONTACT_PERSON_FIRST_NAME = 'First Name';
    protected const LABEL_CONTACT_PERSON_LAST_NAME = 'Last Name';
    protected const LABEL_CONTACT_PERSON_ROLE = 'Role';
    protected const LABEL_NAME = 'Company Name';
    protected const LABEL_REGISTRATION_NUMBER = 'Registration number';
    protected const LABEL_EMAIL = 'Email';
    protected const LABEL_MERCHANT_REFERENCE = 'Merchant Reference';
    protected const LABEL_CONTACT_PERSON_PHONE = 'Phone Number';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'businessInfoMerchantProfile';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdMerchantField($builder)
            ->addContactPersonTitleField($builder)
            ->addContactPersonFirstNameField($builder)
            ->addContactPersonLastNameField($builder)
            ->addContactPersonRoleField($builder)
            ->addNameField($builder)
            ->addEmailField($builder)
            ->addRegistrationNumberField($builder)
            ->addMerchantReferenceField($builder)
            ->addContactPersonPhoneField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdMerchantField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_MERCHANT, HiddenType::class);

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
        $choices = $this->getConfig()->getSalutationChoices();

        $builder->add(static::FIELD_CONTACT_PERSON_TITLE, ChoiceType::class, [
            'choices' => array_flip($choices),
            'required' => false,
            'label' => static::LABEL_CONTACT_PERSON_TITLE,
            'placeholder' => 'select.default.placeholder',
            'property_path' => 'merchantProfile.contactPersonTitle',

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
            'constraints' => $this->getRequiredTextFieldConstraints(),
            'required' => true,
            'property_path' => 'merchantProfile.contactPersonFirstName',
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
            'constraints' => $this->getRequiredTextFieldConstraints(),
            'required' => true,
            'property_path' => 'merchantProfile.contactPersonLastName',
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
            'property_path' => 'merchantProfile.contactPersonRole',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => static::LABEL_NAME,
            'constraints' => $this->getRequiredTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRegistrationNumberField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_REGISTRATION_NUMBER, TextType::class, [
            'label' => static::LABEL_REGISTRATION_NUMBER,
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
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_EMAIL, EmailType::class, [
            'label' => static::LABEL_EMAIL,
            'required' => true,
            'constraints' => $this->getEmailFieldConstraints($this->getCurrentIdFromFormData($builder)),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMerchantReferenceField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::FIELD_MERCHANT_REFERENCE, TextType::class, [
                'label' => static::LABEL_MERCHANT_REFERENCE,
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 255,
                    ]),
                    new UniqueMerchantReference([
                        UniqueMerchantReference::OPTION_CURRENT_MERCHANT_ID => $this->getCurrentIdFromFormData($builder),
                    ]),
                ],
                'disabled' => true,
                'attr' => [
                    'read_only' => true,
                ],
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
            'property_path' => 'merchantProfile.contactPersonPhone',
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
            new Required(),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @param int|null $currentId
     *
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getEmailFieldConstraints(?int $currentId = null): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Email(),
            new Length(['max' => 255]),
            new UniqueEmail([
                UniqueEmail::OPTION_CURRENT_ID_MERCHANT => $currentId,
            ]),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     *
     * @return int|null
     */
    protected function getCurrentIdFromFormData(FormBuilderInterface $formBuilder): ?int
    {
        /** @var \Generated\Shared\Transfer\MerchantTransfer|null $merchantTransfer */
        $merchantTransfer = $formBuilder->getForm()->getData();

        if (!$merchantTransfer) {
            return null;
        }

        return $merchantTransfer->getIdMerchant();
    }
}
