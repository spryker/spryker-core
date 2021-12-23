<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdsRestApi\Communication\Plugin\CartsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderThresholdsRestApi\Business\SalesOrderThresholdsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderThresholdsRestApi\SalesOrderThresholdsRestApiConfig getConfig()
 */
class SalesOrderThresholdQuoteExpanderPlugin extends AbstractPlugin implements QuoteExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `QuoteTransfer.totals` is not set.
     * - Requires `QuoteTransfer.currency` to be set.
     * - Finds applicable thresholds.
     * - Calculates diff between minimal order value threshold and order value amounts.
     * - Translates sales order threshold messages.
     * - Expands quote with sales order thresholds data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->expandQuoteWithSalesOrderThresholdValues($quoteTransfer);
    }
}
