<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Form\Item;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\OfferGui\OfferGuiConfig getConfig()
 */
class ItemType extends AbstractType
{
    public const FIELD_SKU = 'sku';
    public const FIELD_GROUP_KEY = 'groupKey';
    public const FIELD_QUANTITY = 'quantity';
    public const FIELD_OFFER_FEE = 'offerFee';
    public const FIELD_STOCK = 'stock';
    public const FIELD_UNIT_GROSS_PRICE = 'unitGrossPrice';
    public const FIELD_UNIT_NET_PRICE = 'unitNetPrice';
    public const FIELD_SOURCE_UNIT_GROSS_PRICE = 'sourceUnitGrossPrice';
    public const FIELD_SOURCE_UNIT_NET_PRICE = 'sourceUnitNetPrice';
    public const FIELD_OFFER_DISCOUNT = 'offerDiscount';
    public const FIELD_UNIT_SUBTOTAL_AGGREGATION = 'unitSubtotalAggregation';
    public const FIELD_SUM_SUBTOTAL_AGGREGATION = 'sumSubtotalAggregation';

    public const FIELD_FORCED_UNIT_GROSS_PRICE = 'forcedUnitGrossPrice';
    public const FIELD_FORCED_UNIT_NET_PRICE = 'forcedUnitNetPrice';

    protected const ERROR_MESSAGE_PRICE = 'Invalid Price.';
    protected const PATTERN_MONEY = '/^\d*\.?\d{0,2}$/';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addSkuField($builder, $options)
            ->addManualUnitPriceField($builder, $options)
            ->addUnitPriceField($builder, $options)
            ->addOfferDiscountField($builder, $options)
            ->addOfferFeeField($builder, $options)
            ->addStockField($builder, $options)
            ->addQuantityField($builder, $options)
            ->addUnitSubtotalAggregationPriceField($builder, $options)
            ->addSumSubtotalAggregationPriceField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SKU, TextType::class, [
            'label' => 'SKU',
            'required' => true,
            'attr' => [
                'readonly' => true,
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addGroupKeyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_GROUP_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUnitPriceField(FormBuilderInterface $builder, array $options)
    {
        if ($this->isDefaultPriceModeGross()) {
            return $this->addUnitGrossPriceField($builder, $options);
        }

        return $this->addUnitNetPriceField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addManualUnitPriceField(FormBuilderInterface $builder, array $options)
    {
        if ($this->isDefaultPriceModeGross()) {
            return $this->addManualGrossPriceField($builder, $options);
        }

        return $this->addManualNetPriceField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUnitGrossPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_UNIT_GROSS_PRICE, NumberType::class, [
            'label' => 'Gross Price',
            'required' => false,
            'disabled' => true,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        $builder
            ->get(static::FIELD_UNIT_GROSS_PRICE)
            ->addModelTransformer($this->createMoneyModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUnitNetPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_UNIT_NET_PRICE, NumberType::class, [
            'label' => 'Net Price',
            'required' => false,
            'disabled' => true,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        $builder
            ->get(static::FIELD_UNIT_NET_PRICE)
            ->addModelTransformer($this->createMoneyModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addManualGrossPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SOURCE_UNIT_GROSS_PRICE, NumberType::class, [
            'label' => 'Manual Gross Price',
            'required' => false,
            'disabled' => !$this->isDefaultPriceModeGross(),
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        $builder
            ->get(static::FIELD_SOURCE_UNIT_GROSS_PRICE)
            ->addModelTransformer($this->createMoneyModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addManualNetPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SOURCE_UNIT_NET_PRICE, NumberType::class, [
            'label' => 'Manual Net Price',
            'required' => false,
            'disabled' => !$this->isDefaultPriceModeNet(),
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        $builder
            ->get(static::FIELD_SOURCE_UNIT_NET_PRICE)
            ->addModelTransformer($this->createMoneyModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addOfferDiscountField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_OFFER_DISCOUNT, NumberType::class, [
            'label' => 'Offer discount %',
            'required' => false,
            'constraints' => [
                new Range([
                    'min' => 0,
                    'max' => 100,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStockField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_STOCK, TextType::class, [
            'label' => 'Stock',
            'required' => false,
            'disabled' => true,
            'constraints' => [
                $this->createNumberConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addOfferFeeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_OFFER_FEE, NumberType::class, [
            'label' => 'Offer fee',
            'required' => false,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        $builder
            ->get(static::FIELD_OFFER_FEE)
            ->addModelTransformer($this->createMoneyModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUnitSubtotalAggregationPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_UNIT_SUBTOTAL_AGGREGATION, NumberType::class, [
            'label' => 'Unit Subtotal Price',
            'required' => false,
            'disabled' => true,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        $builder
            ->get(static::FIELD_UNIT_SUBTOTAL_AGGREGATION)
            ->addModelTransformer($this->createMoneyModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSumSubtotalAggregationPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SUM_SUBTOTAL_AGGREGATION, NumberType::class, [
            'label' => 'Sum Subtotal Price',
            'required' => false,
            'disabled' => true,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        $builder
            ->get(static::FIELD_SUM_SUBTOTAL_AGGREGATION)
            ->addModelTransformer($this->createMoneyModelTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_QUANTITY, TextType::class, [
            'label' => 'Quantity',
            'required' => false,
            'constraints' => [
                $this->createNumberConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    protected function createNumberConstraint(array $options)
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Regex([
            'pattern' => '/^\d*$/',
            'message' => 'This field should contain digits.',
            'groups' => $validationGroup,
        ]);
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    protected function createMoneyConstraint(array $options)
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Regex([
            'pattern' => static::PATTERN_MONEY,
            'message' => static::ERROR_MESSAGE_PRICE,
            'groups' => $validationGroup,
        ]);
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getValidationGroup(array $options)
    {
        $validationGroup = Constraint::DEFAULT_GROUP;
        if (!empty($options['validation_group'])) {
            $validationGroup = $options['validation_group'];
        }

        return $validationGroup;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createMoneyModelTransformer()
    {
        return new CallbackTransformer(
            function ($value) {
                if ($value !== null) {
                    return $value / 100;
                }
            },
            function ($value) {
                if ($value !== null) {
                    return $value * 100;
                }
            }
        );
    }

    /**
     * @return bool
     */
    protected function isDefaultPriceModeNet()
    {
        return $this->getFactory()->getPriceFacade()->getDefaultPriceMode() === $this->getConfig()->getPriceModeNet();
    }

    /**
     * @return bool
     */
    protected function isDefaultPriceModeGross()
    {
        return $this->getFactory()->getPriceFacade()->getDefaultPriceMode() === $this->getConfig()->getPriceModeGross();
    }
}
