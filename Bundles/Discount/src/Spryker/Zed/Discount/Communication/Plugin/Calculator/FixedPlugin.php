<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class FixedPlugin extends AbstractCalculatorPlugin
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
        return $this->getFacade()->calculateFixedDiscount($discountableItems, $discountTransfer);
    }

    /**
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected function getCurrencyManager()
    {
        return CurrencyManager::getInstance();
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
        return (int)round($this->getCurrencyManager()->convertDecimalToCent($value));
    }

    /**
     * @api
     *
     * @param int $value
     *
     * @return string
     */
    public function transformFromPersistence($value)
    {
        return $this->getCurrencyManager()->format(
            $this->getCurrencyManager()->convertCentToDecimal($value),
            false
        );
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFormattedAmount($amount)
    {
        $discountAmount = $this->getCurrencyManager()->convertCentToDecimal($amount);

        return $this->getCurrencyManager()->format($discountAmount);
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
        ];
    }

}
