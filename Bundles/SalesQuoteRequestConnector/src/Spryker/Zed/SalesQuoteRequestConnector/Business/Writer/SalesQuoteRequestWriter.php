<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuoteRequestConnector\Business\Writer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\SalesQuoteRequestConnector\Persistence\SalesQuoteRequestConnectorEntityManagerInterface;

class SalesQuoteRequestWriter implements SalesQuoteRequestWriterInterface
{
    /**
     * @param \Spryker\Zed\SalesQuoteRequestConnector\Persistence\SalesQuoteRequestConnectorEntityManagerInterface $salesQuoteRequestConnectorEntityManager
     */
    public function __construct(
        protected SalesQuoteRequestConnectorEntityManagerInterface $salesQuoteRequestConnectorEntityManager
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderQuoteRequestVersionReference(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        if (!$quoteTransfer->getQuoteRequestVersionReference()) {
            return;
        }

        $this->salesQuoteRequestConnectorEntityManager->saveOrderQuoteRequestVersionReference(
            $saveOrderTransfer->getIdSalesOrderOrFail(),
            $quoteTransfer->getQuoteRequestVersionReferenceOrFail(),
        );
    }
}
