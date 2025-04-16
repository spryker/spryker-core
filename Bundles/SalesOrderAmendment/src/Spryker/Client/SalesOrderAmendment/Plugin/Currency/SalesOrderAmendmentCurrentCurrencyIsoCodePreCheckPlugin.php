<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment\Plugin\Currency;

use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Client\CurrencyExtension\Dependency\Plugin\CurrentCurrencyIsoCodePreCheckPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentFactory getFactory()
 */
class SalesOrderAmendmentCurrentCurrencyIsoCodePreCheckPlugin extends AbstractPlugin implements CurrentCurrencyIsoCodePreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves the current quote from the session.
     * - Returns `false` if the currency is changed and current `QuoteTransfer.amendmentOrderReference` is not empty.
     * - Adds the corresponding error message to the messenger stack.
     * - Otherwise returns `true`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return bool
     */
    public function isCurrencyChangeAllowed(CurrencyTransfer $currencyTransfer): bool
    {
        return $this->getFactory()->createCurrentCurrencyIsoCodeChecker()->execute($currencyTransfer);
    }
}
