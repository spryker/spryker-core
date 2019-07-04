<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginWithAmountInputTypeInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 */
class PercentagePlugin extends AbstractPlugin implements DiscountCalculatorPluginInterface, DiscountCalculatorPluginWithAmountInputTypeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function calculateDiscount(array $discountableItems, DiscountTransfer $discountTransfer)
    {
        return $this->getFacade()->calculatePercentageDiscount($discountableItems, $discountTransfer);
    }

    /**
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        return (int)round($value * 100);
    }

    /**
     * @api
     *
     * @param int $value
     *
     * @return float
     */
    public function transformFromPersistence($value)
    {
        return (int)round($value / 100);
    }

    /**
     * @api
     *
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return string
     */
    public function getFormattedAmount($amount, $isoCode = null)
    {
        return $this->transformFromPersistence($amount) . ' %';
    }

    /**
     * @api
     *
     * @return array
     */
    public function getAmountValidators()
    {
        return [
            new Type([
                'type' => 'numeric',
                'groups' => DiscountConstants::CALCULATOR_DEFAULT_INPUT_TYPE,
            ]),
            new Range([
                'min' => 1,
                'max' => 100,
                'groups' => DiscountConstants::CALCULATOR_DEFAULT_INPUT_TYPE,
            ]),
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getInputType()
    {
        return DiscountConstants::CALCULATOR_DEFAULT_INPUT_TYPE;
    }
}
