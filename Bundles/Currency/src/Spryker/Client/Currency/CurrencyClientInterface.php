<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency;

/**
 * @method \Spryker\Client\Currency\CurrencyFactory getFactory()
 */
interface CurrencyClientInterface
{
    /**
     * Specification:
     *  - Reads currency data for given iso code, it does not make zed call so it wont have foreign keys to currency table.
     *
     * @api
     *
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function fromIsoCode($isoCode);

    /**
     * Specification:
     *  - Returns current customer session selected currency.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent();

    /**
     * Specification:
     * - Sets selected currency to customer session.
     * - Calls currency post change plugins.
     *
     * @api
     *
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrentCurrencyIsoCode(string $currencyIsoCode): void;
}
