<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\ProductOfferMerchantPortalGuiConfig getConfig()
 */
class PriceProductForm extends AbstractType
{
    public const FIELD_NET_AMOUNT = 'net_amount';
    public const FIELD_GROSS_AMOUNT = 'gross_amount';
    protected const FIELD_FK_PRICE_TYPE = 'fk_price_type';
    protected const FIELD_FK_CURRENCY = 'fk_currency';
    protected const FIELD_FK_STORE = 'fk_store';
    protected const FIELD_ID_PRICE_PRODUCT_OFFER = 'idPriceProductOffer';

    protected const MAX_MONEY_INT = 21474835;
    protected const MIN_MONEY_INT = 0;

    protected const REGULAR_EXPRESSION_MONEY_VALUE = '/[0-9\.\,]+/';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => PriceProductTransfer::class,
            'label' => false,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addFkPriceTypeField($builder)
            ->addNetAmountField($builder)
            ->addGrossAmountField($builder)
            ->addFkCurrencyField($builder)
            ->addFkStoreField($builder)
            ->addIdPriceProductOfferField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkPriceTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_PRICE_TYPE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNetAmountField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NET_AMOUNT, MoneyType::class, [
            'required' => false,
            'currency' => false,
            'property_path' => 'moneyValue.netAmount',
            'divisor' => 100,
            'constraints' => [
                new LessThanOrEqual([
                    'value' => static::MAX_MONEY_INT,
                ]),
                new GreaterThanOrEqual([
                    'value' => static::MIN_MONEY_INT,
                ]),
                new Regex([
                    'pattern' => static::REGULAR_EXPRESSION_MONEY_VALUE,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGrossAmountField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_GROSS_AMOUNT, MoneyType::class, [
            'required' => false,
            'currency' => false,
            'property_path' => 'moneyValue.grossAmount',
            'divisor' => 100,
            'constraints' => [
                new LessThanOrEqual([
                    'value' => static::MAX_MONEY_INT,
                ]),
                new GreaterThanOrEqual([
                    'value' => static::MIN_MONEY_INT,
                ]),
                new Regex([
                    'pattern' => static::REGULAR_EXPRESSION_MONEY_VALUE,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkCurrencyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_CURRENCY, HiddenType::class, [
            'property_path' => 'moneyValue.fkCurrency',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkStoreField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_STORE, HiddenType::class, [
            'property_path' => 'moneyValue.fkStore',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdPriceProductOfferField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRICE_PRODUCT_OFFER, HiddenType::class, [
            'property_path' => 'priceDimension.idPriceProductOffer',
        ]);

        return $this;
    }
}
