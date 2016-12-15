<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class PercentagePlugin extends AbstractCalculatorPlugin
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
     * @return int
     */
    public function transformFromPersistence($value)
    {
        return (int)round($value / 100);
    }

    /**
     * @api
     *
     * @param int $amount
     *
     * @return string
     */
    public function getFormattedAmount($amount)
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
            new Regex([
                'pattern' => '/[0-9\.\,]+/',
            ]),
            new Range([
                'min' => 1,
                'max' => 100,
            ]),
        ];
    }

}
