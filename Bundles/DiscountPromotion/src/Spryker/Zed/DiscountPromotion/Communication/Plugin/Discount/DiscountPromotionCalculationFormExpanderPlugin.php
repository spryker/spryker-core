<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConstants;
use Spryker\Zed\Discount\Communication\Form\CalculatorForm;
use Spryker\Zed\Discount\Dependency\Plugin\Form\DiscountFormExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacade getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 */
class DiscountPromotionCalculationFormExpanderPlugin extends AbstractPlugin implements DiscountFormExpanderPluginInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expandFormType(FormBuilderInterface $builder, array $options)
    {
        return $builder->add(
            DiscountCalculatorTransfer::DISCOUNT_PROMOTION,
            $this->getFactory()->createDiscountFormPromotionType(),
            [
                'data_class' => DiscountPromotionTransfer::class,
                'label' => false,
            ]
        );
    }

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param array $options
     *
     * @return array
     */
    public function expandDataProviderOptions(array $options)
    {
        $options[CalculatorForm::OPTION_COLLECTOR_TYPE_CHOICES][DiscountPromotionConstants::DISCOUNT_COLLECTOR_STRATEGY] = 'Discount promotion to product';

        return $options;
    }

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @param array $data
     *
     * @return array
     */
    public function expandDataProviderData(array $data)
    {
        return $data;
    }

    /**
     * @api
     *
     * {@inheritdoc}
     *
     * @return string
     */
    public function getFormTypeToExtend()
    {
        return DiscountFormExpanderPluginInterface::FORM_TYPE_CALCULATION;
    }

}
