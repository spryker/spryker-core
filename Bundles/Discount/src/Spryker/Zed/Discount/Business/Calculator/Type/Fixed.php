<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator\Type;

use Generated\Shared\Transfer\DiscountTransfer;

class Fixed implements CalculatorInterface
{

    /**
     * @deprecated use calculateDiscount instead
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param int $amount
     *
     * @return int
     */
    public function calculate(array $discountableItems, $amount)
    {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->setAmount($amount);

        return $this->calculateDiscount($discountableItems, $discountTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function calculateDiscount(array $discountableItems, DiscountTransfer $discountTransfer)
    {
        $amount = $this->getDiscountAmountForCurrentCurrency($discountTransfer);
        if ($amount <= 0) {
            return 0;
        }

        return $amount;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    protected function getDiscountAmountForCurrentCurrency(DiscountTransfer $discountTransfer)
    {
        foreach ($discountTransfer->getDiscountMoneyAmounts() as $moneyAmountTransfer) {
            if ($discountTransfer->getCurrency()->getCode() === $moneyAmountTransfer->getCurrencyIsoCode()) {
                return $moneyAmountTransfer->getAmount();
            }
        }

        return 0;
    }

}
