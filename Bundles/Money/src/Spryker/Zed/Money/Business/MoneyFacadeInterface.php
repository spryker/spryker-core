<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Money\Business;

use Generated\Shared\Transfer\MoneyTransfer;

/**
 * @method \Spryker\Zed\Money\Business\MoneyBusinessFactory getFactory()
 */
interface MoneyFacadeInterface
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
    public function getMoney($amount, $currency = null);

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
    public function formatWithSymbol(MoneyTransfer $moneyTransfer);

    /**
     * Specification:
     * - Converts MoneyTransfer Object into string representation without currency symbol
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithoutSymbol(MoneyTransfer $moneyTransfer);

    /**
     * Specification
     * - Converts a cent value into decimal value
     *
     * @api
     *
     * @param int $value
     *
     * @return float
     */
    public function convertCentToDecimal($value);

    /**
     * Specification
     * - Converts a decimal value into cent value
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function convertDecimalToCent($value);

}
