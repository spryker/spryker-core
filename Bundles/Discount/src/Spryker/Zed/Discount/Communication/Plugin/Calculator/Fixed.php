<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class Fixed extends AbstractPlugin implements DiscountCalculatorPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param int $number
     *
     * @return float
     */
    public function calculate(array $discountableItems, $number)
    {
        return $this->getFacade()->calculateFixed($discountableItems, $number);
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
