<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CurrencyExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CurrencyTransfer;

/**
 * Implement this plugin interface to check if currency can be changed.
 */
interface CurrentCurrencyIsoCodePreCheckPluginInterface
{
    /**
     * Specification:
     * - Checks if the provided currency is valid to be changed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return bool
     */
    public function isCurrencyChangeAllowed(CurrencyTransfer $currencyTransfer): bool;
}
