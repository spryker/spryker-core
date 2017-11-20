<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Dependency\Plugin;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;

interface MoneyPluginInterface
{
    /**
     * Specification:
     * - Returns a MoneyTransfer object created from given integer value.
     *
     * @api
     *
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger($amount, $isoCode = null);

    /**
     * Specification:
     * - Returns a MoneyTransfer object created from given float value.
     *
     * @api
     *
     * @param float $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromFloat($amount, $isoCode = null);

    /**
     * Specification:
     * - Returns a MoneyTransfer object created from given string value.
     *
     * @api
     *
     * @param string $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromString($amount, $isoCode = null);

    /**
     * Specification:
     * - Returns formatted string representation of the given MoneyTransfer object with currency symbol
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `CurrencyTransfer::$code = EUR` will return `10,00 €`
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer);

    /**
     * Specification:
     * - Returns float representation of the given MoneyTransfer object without symbol
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `CurrencyTransfer::$code = EUR` will return `10,00`
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer);

    /**
     * Specification:
     * - Returns a MoneyTransfer parsed from the given value.
     *
     * E.g. `$value = 10,00 €` `CurrencyTransfer::$code = EUR` will return `MoneyTransfer::$amount(1000)`
     *
     * @api
     *
     * @param string $value
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function parse($value, CurrencyTransfer $currencyTransfer);

    /**
     * Specification:
     * - Returns from a given integer value converted decimal value.
     *
     * E.g. `$value = 1000` will return `10.00`
     *
     * @api
     *
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value);

    /**
     * Specification:
     * - Returns from a given decimal value converted integer value.
     *
     * E.g. `$value = 10.00` will return `1000`
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToInteger($value);
}
