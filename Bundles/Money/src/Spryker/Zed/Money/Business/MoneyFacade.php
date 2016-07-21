<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Business;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Money\MoneyConstants;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Money\Business\MoneyBusinessFactory getFactory()
 */
class MoneyFacade extends AbstractFacade
{

    /**
     * Specification:
     * - Converts int amount and currency to MoneyTransfer Object
     * - Converts float amount and currency to MoneyTransfer Object
     * - If currency is not provided it will use configured one from Store
     *
     * @api
     *
     * @param int|float $amount
     * @param string|null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function getMoney($amount, $currency = null)
    {
        return $this->getFactory()->createMoneyBuilder()->getMoney($amount, $currency);
    }

    /**
     * Specification:
     * - Converts MoneyTransfer Object into string representation with currency symbol
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this->getFactory()->createMoneyFormatter()->format(
            $moneyTransfer,
            MoneyConstants::FORMATTER_WITH_CURRENCY
        );
    }

    /**
     * Specification:
     * - Converts MoneyTransfer Object into string representation with currency symbol
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this->getFactory()->createMoneyFormatter()->format(
            $moneyTransfer,
            MoneyConstants::FORMATTER_WITHOUT_CURRENCY
        );
    }

}
