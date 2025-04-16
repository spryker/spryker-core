<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment\Plugin\Price;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceExtension\Dependency\Plugin\CurrentPriceModePreCheckPluginInterface;

/**
 * @method \Spryker\Client\SalesOrderAmendment\SalesOrderAmendmentFactory getFactory()
 */
class SalesOrderAmendmentCurrentPriceModePreCheckPlugin extends AbstractPlugin implements CurrentPriceModePreCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns `false` if the price mode is changed and `QuoteTransfer.amendmentOrderReference` is not empty.
     * - Adds the corresponding error message to the messenger stack.
     * - Otherwise returns `true`.
     *
     * @api
     *
     * @param string $priceMode
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isPriceModeChangeAllowed(string $priceMode, QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->createCurrentPriceModeChecker()->execute($priceMode, $quoteTransfer);
    }
}
