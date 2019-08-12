<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Closure;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $builder->add(static::FIELD_NET_AMOUNT, NumberType::class, [
            'label' => 'Net price',
            'required' => false,
            'attr' => [
                'value' => null,
            ],
        ]);

        $builder->get(static::FIELD_NET_AMOUNT)
            ->addModelTransformer($this->createModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    protected function createModelTransformer(): DataTransformerInterface
    {
        return new CallbackTransformer(
            $this->createTransformCallback(),
            $this->createReverseTransformCallback()
        );
    }

    /**
     * @return \Closure
     */
    protected function createTransformCallback(): Closure
    {
        return function ($amount) {
            if ($amount === null) {
                return null;
            }

            return $amount / 100;
        };
    }

    /**
     * @return \Closure
     */
    protected function createReverseTransformCallback(): Closure
    {
        return function ($amount) {
            if ($amount === null) {
                return null;
            }

            return (int)($amount * 100);
        };
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGrossPrice(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_GROSS_AMOUNT, NumberType::class, [
            'label' => 'Gross price',
            'required' => false,
            'attr' => [
                'value' => null,
            ],
        ]);

        $builder->get(static::FIELD_GROSS_AMOUNT)
            ->addModelTransformer($this->createModelTransformer());

        return $this;
    }
}
