<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Money;

use Generated\Shared\Transfer\MoneyTransfer;

interface MoneyClientInterface
{
    /**
     * Specification:
     * - Returns a MoneyTransfer object created from given integer value.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer): string;

    /**
     * Specification:
     * - Returns formatted string representation of the given MoneyTransfer object with currency symbol.
     *
     * E.g. `MoneyTransfer::$amount = 1000`, `CurrencyTransfer::$code = EUR` will return `10,00 €`.
     *
     * @api
     *
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger(int $amount, ?string $isoCode): MoneyTransfer;
}
