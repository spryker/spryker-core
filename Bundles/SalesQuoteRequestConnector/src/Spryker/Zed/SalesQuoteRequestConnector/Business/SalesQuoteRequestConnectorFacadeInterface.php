<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuoteRequestConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface SalesQuoteRequestConnectorFacadeInterface
{
    /**
     * Specification:
     * - Requires `SaveOrderTransfer.idSalesOrder` to be set.
     * - Expects `SaveOrderTransfer.quoteRequestVersionReference` to be provided.
     * - Persists `QuoteTransfer.quoteRequestVersionReference` transfer property in `spy_sales_order` table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderQuoteRequestVersionReference(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void;
}
