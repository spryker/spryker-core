<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Money\Plugin;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Money\MoneyFactory getFactory()
 */
class MoneyPlugin extends AbstractPlugin implements MoneyPluginInterface
{
    /**
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger($amount, $isoCode = null)
    {
        return $this->getFactory()->createMoneyBuilder()->fromInteger($amount, $isoCode);
    }

    /**
     * @param float $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromFloat($amount, $isoCode = null)
    {
        return $this->getFactory()->createMoneyBuilder()->fromFloat($amount, $isoCode);
    }

    /**
     * @param string $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromString($amount, $isoCode = null)
    {
        return $this->getFactory()->createMoneyBuilder()->fromString($amount, $isoCode);
    }

    /**
     * This method will return formatted string representation of the given MoneyTransfer object with currency symbol
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `MoneyTransfer::$isoCode = EUR` will return `10,00 €`
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this->getFactory()->createMoneyFormatter()->format($moneyTransfer, MoneyFormatterCollection::FORMATTER_WITH_SYMBOL);
    }

    /**
     * This method will return float representation of the given MoneyTransfer object without symbol
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `MoneyTransfer::$isoCode = EUR` will return `10,00`
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this->getFactory()->createMoneyFormatter()->format($moneyTransfer, MoneyFormatterCollection::FORMATTER_WITHOUT_SYMBOL);
    }

    /**
     * This money will return a MoneyTransfer parsed from the given value.
     *
     * E.g. `$value = 10,00 €` `CurrencyTransfer::$code = EUR` will return `MoneyTransfer::$amount(1000)`
     *
     * @param string $value
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function parse($value, CurrencyTransfer $currencyTransfer)
    {
        return $this->getFactory()->createMoneyParser()->parse($value, $currencyTransfer);
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value)
    {
        return $this->getFactory()->createIntegerToDecimalConverter()->convert($value);
    }

    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger($value)
    {
        return $this->getFactory()->createDecimalToIntegerConverter()->convert($value);
    }
}
