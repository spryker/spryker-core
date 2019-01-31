<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class MerchantForm extends AbstractType
{
    protected const FIELD_ID_MERCHANT = 'id_merchant';
    protected const FIELD_NAME = 'name';
    protected const FIELD_REGISTRATION_NUMBER = 'registration_number';
    protected const FIELD_CONTACT_PERSON_FIRST_NAME = 'contact_person_first_name';
    protected const FIELD_CONTACT_PERSON_LAST_NAME = 'contact_person_last_name';
    protected const FIELD_CONTACT_PERSON_PHONE = 'contact_person_phone';
    protected const FIELD_CONTACT_PERSON_TITLE = 'contact_person_title';
    protected const FIELD_EMAIL = 'email';

    protected const LABEL_NAME = 'Name';
    protected const LABEL_REGISTRATION_NUMBER = 'Registration number';
    protected const LABEL_CONTACT_PERSON_FIRST_NAME = 'Contact person first name';
    protected const LABEL_CONTACT_PERSON_LAST_NAME = 'Contact person last name';
    protected const LABEL_CONTACT_PERSON_PHONE = 'Contact person phone';
    protected const LABEL_CONTACT_PERSON_TITLE = 'Contact person title';
    protected const LABEL_EMAIL = 'Email';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'merchant';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdMerchantField($builder)
            ->addNameField($builder)
            ->addEmailField($builder)
            ->addRegistrationNumberField($builder)
            ->addContactPersonTitleField($builder)
            ->addContactPersonFirstNameField($builder)
            ->addContactPersonLastNameField($builder)
            ->addContactPersonPhoneField($builder)
            ->addAddressSubform($builder);
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
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => static::LABEL_NAME,
            'constraints' => $this->getTextFieldConstraints(),
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
            'constraints' => $this->getTextFieldConstraints(),
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
            'constraints' => $this->getEmailFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addContactPersonTitleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_TITLE, TextType::class, [
            'label' => static::LABEL_CONTACT_PERSON_TITLE,
            'constraints' => $this->getTextFieldConstraints(),
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
            'constraints' => $this->getTextFieldConstraints(),
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
            'constraints' => $this->getTextFieldConstraints(),
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
            'constraints' => $this->getNumberTextFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddressSubform(FormBuilderInterface $builder)
    {
        $merchantAddressFormDataProvider = $this->getFactory()->createMerchantAddressFormDataProvider();

        $builder->add(
            'addresses',
            MerchantAddressForm::class,
            $merchantAddressFormDataProvider->getOptions()
        );

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getTextFieldConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getNumberTextFieldConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Type(['type' => 'numeric']),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getEmailFieldConstraints(): array
    {
        $merchantFacade = $this->getFactory()->getMerchantFacade();

        return [
            new Required(),
            new NotBlank(),
            new Email(),
            new Length(['max' => 255]),
            new Callback([
                'callback' => function ($email, ExecutionContextInterface $context) use ($merchantFacade) {
                    if ($merchantFacade->findMerchantByEmail((new MerchantTransfer())->setEmail($email)) !== null) {
                        $context->addViolation('Email is already used');
                    }
                },
            ]),
        ];
    }
}
