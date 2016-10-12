<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class PercentagePlugin extends AbstractPlugin implements DiscountCalculatorPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param float $percentage
     *
     * @return float
     */
    public function calculate(array $discountableItems, $percentage)
    {
        return $this->getFacade()->calculatePercentage($discountableItems, $percentage);
    }

    /**
     * @param string|float $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        $value = str_replace(',', '.', $value);

        return (int)round($value * 100);
    }

    /**
     * @param int $value
     *
     * @return int
     */
    public function transformFromPersistence($value)
    {
        return (int)round($value / 100);
    }

    /**
     * @param int $amount
     *
     * @return string
     */
    public function getFormattedAmount($amount)
    {
        return $this->transformFromPersistence($amount) . ' %';
    }

    /**
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
