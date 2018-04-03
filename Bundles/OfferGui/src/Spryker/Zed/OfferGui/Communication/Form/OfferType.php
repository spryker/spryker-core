<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Form;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\OfferGui\Communication\Form\Item\IncomingItemType;
use Spryker\Zed\OfferGui\Communication\Form\Item\ItemType;
use Spryker\Zed\OfferGui\Communication\Form\Voucher\VoucherType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class OfferType extends AbstractType
{
    public const FIELD_ID_OFFER = 'idOffer';
    public const FIELD_ITEMS = 'items';
    public const FIELD_INCOMING_ITEMS = 'incomingItems';
    public const FIELD_VOUCHER_DISCOUNTS = 'voucherDiscounts';

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
            ->addItemsField($builder)
            ->addIncomingItemsField($builder)
            ->addVoucherDiscountsField($builder);
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
