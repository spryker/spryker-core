<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceProductSubForm extends AbstractType
{
    public const FIELD_PRICE_TYPE = 'priceType';
    public const FIELD_MONEY_VALUE = 'moneyValue';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMoneyValue($builder)
            ->addPriceType($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPriceType(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRICE_TYPE, PriceTypeSubForm::class, [
            'label' => false,
            'data_class' => PriceTypeTransfer::class,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMoneyValue(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_MONEY_VALUE, MoneyValueSubForm::class, [
            'label' => false,
            'data_class' => MoneyValueTransfer::class,
        ]);

        return $this;
    }
}
