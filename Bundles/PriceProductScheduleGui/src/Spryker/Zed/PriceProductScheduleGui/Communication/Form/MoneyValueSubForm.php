<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class MoneyValueSubForm extends AbstractType
{
    public const FIELD_NET_AMOUNT = 'netAmount';
    public const FIELD_GROSS_AMOUNT = 'grossAmount';
    public const FIELD_CURRENCY = 'currency';
    public const FIELD_STORE = 'store';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCurrency(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CURRENCY, CurrencySubForm::class, [
            'label' => false,
            'data_class' => CurrencyTransfer::class,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addStore(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_STORE, StoreSubForm::class, [
            'label' => false,
            'data_class' => StoreTransfer::class,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStore($builder)
            ->addCurrency($builder)
            ->addNetPrice($builder)
            ->addGrossPrice($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNetPrice(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NET_AMOUNT, IntegerType::class, [
            'label' => 'Net price',
            'constraints' => [
                new NotBlank(),
                new GreaterThan([
                    'value' => 0,
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
    protected function addGrossPrice(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_GROSS_AMOUNT, IntegerType::class, [
            'label' => 'Gross price',
            'constraints' => [
                new NotBlank(),
                new GreaterThan([
                    'value' => 0,
                ]),
            ],
        ]);

        return $this;
    }
}
