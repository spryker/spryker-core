<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class MerchantUpdateForm extends MerchantForm
{
    public const OPTION_STATUS_CHOICES = 'status_choices';
    protected const FIELD_STATUS = 'status';

    protected const LABEL_STATUS = 'Status';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_STATUS_CHOICES);
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
            ->addStatusField($builder, $options[static::OPTION_STATUS_CHOICES])
            ->addAddressSubform($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addStatusField(FormBuilderInterface $builder, array $choices = [])
    {
        $builder->add(static::FIELD_STATUS, ChoiceType::class, [
            'label' => static::LABEL_STATUS,
            'constraints' => $this->getStatusFieldConstraints($choices),
            'choices' => array_flip($choices),
            'choices_as_values' => true,
            'placeholder' => false,
        ]);

        return $this;
    }

    /**
     * @param array $choices
     *
     * @return array
     */
    protected function getStatusFieldConstraints(array $choices = []): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 64]),
            new Choice(['choices' => array_keys($choices)]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getEmailFieldConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Email(),
            new Length(['max' => 255]),
        ];
    }
}
