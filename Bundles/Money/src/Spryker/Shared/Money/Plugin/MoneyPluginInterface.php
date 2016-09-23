<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Plugin;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;

interface MoneyPluginInterface
{

    /**
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger($amount, $isoCode = null);

    /**
     * @param float $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromFloat($amount, $isoCode = null);

    /**
     * @param string $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromString($amount, $isoCode = null);

    /**
     * This method will return formatted string representation of the given MoneyTransfer object with currency symbol
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `CurrencyTransfer::$code = EUR` will return `10,00 €`
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer);

    /**
     * This method will return float representation of the given MoneyTransfer object without symbol
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `CurrencyTransfer::$code = EUR` will return `10,00`
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer);

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
    public function parse($value, CurrencyTransfer $currencyTransfer);

    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value);

    /**
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger($value);

}
