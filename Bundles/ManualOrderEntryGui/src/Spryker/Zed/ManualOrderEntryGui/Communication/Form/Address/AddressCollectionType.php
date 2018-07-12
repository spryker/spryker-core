<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;

class AddressCollectionType extends AbstractType
{
    public const TYPE_NAME = 'addresses';

    public const FIELD_SHIPPING_ADDRESS = 'shippingAddress';
    public const FIELD_BILLING_ADDRESS = 'billingAddress';
    public const FIELD_BILLING_SAME_AS_SHIPPING = 'billingSameAsShipping';

    public const OPTION_ADDRESS_CHOICES = 'address_choices';
    public const OPTION_COUNTRY_CHOICES = 'country_choices';

    public const GROUP_SHIPPING_ADDRESS = self::FIELD_SHIPPING_ADDRESS;
    public const GROUP_BILLING_ADDRESS = self::FIELD_BILLING_ADDRESS;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        /** @var \Symfony\Component\OptionsResolver\OptionsResolver $resolver */
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $validationGroups = [Constraint::DEFAULT_GROUP, static::GROUP_SHIPPING_ADDRESS];

                if (!$form->get(static::FIELD_BILLING_SAME_AS_SHIPPING)->getData()) {
                    $validationGroups[] = static::GROUP_BILLING_ADDRESS;
                }

                return $validationGroups;
            },
            static::OPTION_ADDRESS_CHOICES => [],
        ]);

        $resolver->setDefined(static::OPTION_ADDRESS_CHOICES);
        $resolver->setRequired(static::OPTION_COUNTRY_CHOICES);
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
            ->addShippingAddressSubForm($builder, $options)
            ->addSameAsShipmentCheckbox($builder)
            ->addBillingAddressSubForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShippingAddressSubForm(FormBuilderInterface $builder, array $options)
    {
        $options = [
            'data_class' => AddressTransfer::class,
            'allow_extra_fields' => true,
            'required' => false,
            'validation_groups' => function (FormInterface $form) {
                if (!$form->has(AddressSelectionType::FIELD_ID_CUSTOMER_ADDRESS) || !$form->get(AddressSelectionType::FIELD_ID_CUSTOMER_ADDRESS)->getData()) {
                    return [static::GROUP_SHIPPING_ADDRESS];
                }

                return false;
            },
            AddressSelectionType::OPTION_VALIDATION_GROUP => static::GROUP_SHIPPING_ADDRESS,
            AddressSelectionType::OPTION_ADDRESS_CHOICES => $options[static::OPTION_ADDRESS_CHOICES],
            AddressSelectionType::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
        ];

        $builder->add(static::FIELD_SHIPPING_ADDRESS, AddressSelectionType::class, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSameAsShipmentCheckbox(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_BILLING_SAME_AS_SHIPPING,
            CheckboxType::class,
            [
                'required' => false,
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addBillingAddressSubForm(FormBuilderInterface $builder, array $options)
    {
        $options = [
            'data_class' => AddressTransfer::class,
            'allow_extra_fields' => true,
            'validation_groups' => function (FormInterface $form) {
                if ($form->getParent()->get(static::FIELD_BILLING_SAME_AS_SHIPPING)->getData()) {
                    return false;
                }

                if (!$form->has(AddressSelectionType::FIELD_ID_CUSTOMER_ADDRESS) || !$form->get(AddressSelectionType::FIELD_ID_CUSTOMER_ADDRESS)->getData()) {
                    return [static::GROUP_BILLING_ADDRESS];
                }

                return false;
            },
            'required' => false,
            AddressSelectionType::OPTION_VALIDATION_GROUP => static::GROUP_BILLING_ADDRESS,
            AddressSelectionType::OPTION_ADDRESS_CHOICES => $options[static::OPTION_ADDRESS_CHOICES],
            AddressSelectionType::OPTION_COUNTRY_CHOICES => $options[static::OPTION_COUNTRY_CHOICES],
        ];

        $builder->add(static::FIELD_BILLING_ADDRESS, AddressSelectionType::class, $options);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::TYPE_NAME;
    }
}
