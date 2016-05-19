<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Discount\Communication\Plugin\AbstractDiscountPlugin;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class Fixed extends AbstractDiscountPlugin implements DiscountCalculatorPluginInterface
{

    /**
     * @param DiscountableItemTransfer[] $discountableItems
     * @param int $number
     *
     * @return float
     */
    public function calculate(array $discountableItems, $number)
    {
        return $this->getFacade()->calculateFixed($discountableItems, $number);
    }

    /**
     * @param int $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        return $this->getCurrencyManager()->convertDecimalToCent($value);
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function transformFromPersistence($value)
    {
        return $this->getCurrencyManager()->convertCentToDecimal($value);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return string
     */
    public function getFormattedAmount(DiscountTransfer $discountTransfer)
    {
        $currencyManager = CurrencyManager::getInstance();
        $discountAmount = $currencyManager->convertCentToDecimal($discountTransfer->getAmount());

        return $currencyManager->format($discountAmount);
    }

}
