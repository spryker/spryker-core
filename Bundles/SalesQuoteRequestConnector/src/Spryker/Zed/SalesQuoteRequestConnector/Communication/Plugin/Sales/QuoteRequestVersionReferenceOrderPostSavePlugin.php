<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuoteRequestConnector\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface;

/**
 * @method \Spryker\Zed\SalesQuoteRequestConnector\SalesQuoteRequestConnectorConfig getConfig()
 * @method \Spryker\Zed\SalesQuoteRequestConnector\Business\SalesQuoteRequestConnectorFacadeInterface getFacade()
 */
class QuoteRequestVersionReferenceOrderPostSavePlugin extends AbstractPlugin implements OrderPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SaveOrderTransfer.idSalesOrder` to be set.
     * - Expects `SaveOrderTransfer.quoteRequestVersionReference` to be provided.
     * - Persists `QuoteTransfer.quoteRequestVersionReference` transfer property in `spy_sales_order` table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function execute(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        $this->getFacade()->saveOrderQuoteRequestVersionReference($quoteTransfer, $saveOrderTransfer);

        return $saveOrderTransfer;
    }
}
