<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider\PriceProductScheduleFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig getConfig()
 */
class PriceProductSubForm extends AbstractType
{
    public const FIELD_PRICE_TYPE = 'priceType';
    public const FIELD_MONEY_VALUE = 'moneyValue';
    public const FIELD_ABSTRACT_SKU = 'skuProductAbstract';
    public const FIELD_CONCRETE_SKU = 'skuProduct';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined([
            PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED,
        ]);

        $resolver->setRequired([
            PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES,
            PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED,
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
        $this->addAbstractSku($builder)
            ->addConcreteSku($builder)
            ->addMoneyValue($builder, $options)
            ->addPriceType($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPriceType(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PRICE_TYPE, PriceTypeSubForm::class, [
            'label' => false,
            'data_class' => PriceTypeTransfer::class,
            PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES => $options[PriceProductScheduleFormDataProvider::OPTION_PRICE_TYPE_CHOICES],
            PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED => $options[PriceProductScheduleFormDataProvider::OPTION_IS_PRICE_TYPE_DISABLED],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addMoneyValue(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_MONEY_VALUE, MoneyValueSubForm::class, [
            'label' => false,
            'data_class' => MoneyValueTransfer::class,
            PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES => $options[PriceProductScheduleFormDataProvider::OPTION_STORE_CHOICES],
            PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES => $options[PriceProductScheduleFormDataProvider::OPTION_CURRENCY_CHOICES],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAbstractSku(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ABSTRACT_SKU, TextType::class, [
            'label' => 'Abstract SKU',
            'disabled' => true,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addConcreteSku(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONCRETE_SKU, TextType::class, [
            'label' => 'Concrete SKU',
            'disabled' => true,
            'required' => false,
        ]);

        return $this;
    }
}
