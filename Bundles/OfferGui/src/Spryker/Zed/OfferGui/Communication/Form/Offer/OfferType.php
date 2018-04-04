<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Form\Offer;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\OfferGui\Communication\Form\Address\AddressType;
use Spryker\Zed\OfferGui\Communication\Form\Customer\CustomerChoiceType;
use Spryker\Zed\OfferGui\Communication\Form\Item\IncomingItemType;
use Spryker\Zed\OfferGui\Communication\Form\Item\ItemType;
use Spryker\Zed\OfferGui\Communication\Form\Voucher\VoucherType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public const FIELD_ID_OFFER = 'idOffer';
    public const FIELD_ITEMS = 'items';
    public const FIELD_INCOMING_ITEMS = 'incomingItems';
    public const FIELD_VOUCHER_DISCOUNTS = 'voucherDiscounts';
    public const FIELD_CUSTOMER_REFERENCE = 'customerReference';
    public const FIELD_QUOTE_SHIPPING_ADDRESS = 'shippingAddress';
    public const FIELD_QUOTE_BILLING_ADDRESS = 'billingAddress';

    public const OPTION_CUSTOMER_LIST = 'option-customer-list';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addIdOfferField($builder)
            ->addCustomerChoice($builder, $options)
            ->addShippingAddress($builder, $options)
            ->addBillingAddress($builder, $options)
            ->addItemsField($builder)
            ->addIncomingItemsField($builder)
            ->addVoucherDiscountsField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_CUSTOMER_LIST);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdOfferField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_OFFER, HiddenType::class);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCustomerChoice(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_CUSTOMER_REFERENCE, Select2ComboBoxType::class, [
            'label' => 'Select Customer',
            'choices' => array_flip($options[static::OPTION_CUSTOMER_LIST]),
            'multiple' => false,
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShippingAddress(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_QUOTE_SHIPPING_ADDRESS, AddressType::class, [
            'property_path' => 'quote.shippingAddress',
            'label' => 'Shipping address',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addBillingAddress(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_QUOTE_BILLING_ADDRESS, AddressType::class, [
            'property_path' => 'quote.billingAddress',
            'label' => 'Billing address',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addItemsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ITEMS, CollectionType::class, [
            'entry_type' => ItemType::class,
            'property_path' => 'quote.items',
            'label' => 'Added Items',
            'required' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'label' => false,
                'data_class' => ItemTransfer::class,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIncomingItemsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_INCOMING_ITEMS, CollectionType::class, [
            'entry_type' => IncomingItemType::class,
            'property_path' => 'quote.incomingItems',
            'label' => 'New items',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'label' => false,
                'data_class' => ItemTransfer::class,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addVoucherDiscountsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_VOUCHER_DISCOUNTS, CollectionType::class, [
            'label' => false,
            'entry_type' => VoucherType::class,
            'property_path' => 'quote.voucherDiscounts',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
                'label' => false,
                'data_class' => DiscountTransfer::class,
            ],
        ]);

        return $this;
    }
}
