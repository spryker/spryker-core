<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @method \Spryker\Zed\PriceProductVolumeGui\Business\PriceProductVolumeGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductVolumeGui\Communication\PriceProductVolumeGuiCommunicationFactory getFactory()
 */
class PriceVolumeFormType extends AbstractType
{
    protected const FIELD_NET_PRICE = 'net_price';
    protected const FIELD_GROSS_PRICE = 'gross_price';
    public const FIELD_QUANTITY = 'quantity';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addQuantityField($builder)
            ->addGrossPriceField($builder)
            ->addNetPriceField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_QUANTITY, TextType::class, [
            'label' => 'Quantity',
            'required' => false,
            'constraints' => [
                new Required(),
                new Regex(['pattern' => '/[\d]+/']),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNetPriceField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NET_PRICE, MoneyType::class, [
            'label' => 'Net Price',
            'currency' => 'EUR', //todo: set up according to param
            'required' => false,
            'constraints' => [
                new GreaterThanOrEqual(0),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addGrossPriceField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_GROSS_PRICE, MoneyType::class, [
            'label' => 'Gross Price',
            'currency' => 'EUR', //todo: set up according to param
            'required' => false,
            'constraints' => [
                new GreaterThanOrEqual(0),
            ],
        ]);

        return $this;
    }
}
