<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Money\Plugin\Money;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Money\MoneyConstants;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Money\MoneyFactory getFactory()
 */
class MoneyPlugin extends AbstractPlugin
{

    /**
     * @param int $amount
     * @param string|null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger($amount, $currency = null)
    {
        return $this->getFactory()->createMoneyBuilder()->fromInteger($amount, $currency);
    }

    /**
     * @param float $amount
     * @param string|null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromFloat($amount, $currency = null)
    {
        return $this->getFactory()->createMoneyBuilder()->fromFloat($amount, $currency);
    }

    /**
     * @param string $amount
     * @param string|null $currency
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromString($amount, $currency = null)
    {
        return $this->getFactory()->createMoneyBuilder()->fromString($amount, $currency);
    }

    /**
     * This method will return formatted string representation of the given MoneyTransfer object with currency symbol
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `MoneyTransfer::$currency = EUR` will return `10,00 €`
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this->getFactory()->createMoneyFormatter()->format($moneyTransfer, MoneyConstants::FORMATTER_WITH_SYMBOL);
    }

    /**
     * This method will return float representation of the given MoneyTransfer object without symbol
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `MoneyTransfer::$currency = EUR` will return `10,00`
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this->getFactory()->createMoneyFormatter()->format($moneyTransfer, MoneyConstants::FORMATTER_WITHOUT_SYMBOL);
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function convertCentToDecimal($value)
    {
        return $this->getFactory()->createCentToDecimalConverter()->convert($value);
    }

    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToCent($value)
    {
        return $this->getFactory()->createDecimalToCentConverter()->convert($value);
    }

}
