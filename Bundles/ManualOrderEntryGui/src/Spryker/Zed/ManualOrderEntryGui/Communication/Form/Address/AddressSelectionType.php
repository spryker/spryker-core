<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressSelectionType extends AddressType
{
    public const OPTION_VALIDATION_GROUP = 'validation_group';
    public const OPTION_ADDRESS_CHOICES = 'addresses_choices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_ADDRESS_CHOICES => [],
        ]);

        $resolver->setRequired(static::OPTION_VALIDATION_GROUP);
        $resolver->setDefined(static::OPTION_ADDRESS_CHOICES);
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
            ->addAddressSelectField($builder, $options)
            ->addSalutationField($builder, $options)
            ->addFirstNameField($builder, $options)
            ->addLastNameField($builder, $options)
            ->addCompanyField($builder, $options)
            ->addAddress1Field($builder, $options)
            ->addAddress2Field($builder, $options)
            ->addAddress3Field($builder, $options)
            ->addZipCodeField($builder, $options)
            ->addCityField($builder, $options)
            ->addIso2CodeField($builder, $options)
            ->addPhoneField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAddressSelectField(FormBuilderInterface $builder, array $options): self
    {
        if (count($options[static::OPTION_ADDRESS_CHOICES]) === 0) {
            return $this;
        }

        $choices = $options[static::OPTION_ADDRESS_CHOICES];
        $choices[''] = 'Add new address';

        $builder->add(static::FIELD_ID_CUSTOMER_ADDRESS, ChoiceType::class, [
            'choices' => array_flip($choices),
            'required' => true,
            'expanded' => true,
            'multiple' => false,
        ]);

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(array $options): NotBlank
    {
        return new NotBlank(['groups' => $options[static::OPTION_VALIDATION_GROUP]]);
    }
}
