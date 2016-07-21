<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Money\Plugin\Money;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Money\MoneyFactory getFactory()
 */
class MoneyPlugin extends AbstractPlugin
{

    /**
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
     * This method will return formatted string representation of the given MoneyTransfer object
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `MoneyTransfer::$currency = EUR` will return `10,00 €`
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function format(MoneyTransfer $moneyTransfer)
    {
        return $this->getFactory()->createMoneyFormatter()->format($moneyTransfer);
    }

    /**
     * This method will return float representation of the given MoneyTransfer object
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `MoneyTransfer::$currency = EUR` will return `10,00`
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatRaw(MoneyTransfer $moneyTransfer)
    {
        return $moneyTransfer->getAmount();
    }

}
