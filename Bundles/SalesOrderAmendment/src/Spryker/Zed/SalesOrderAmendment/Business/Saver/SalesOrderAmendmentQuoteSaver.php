<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Saver;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentQuoteCreatorInterface;
use Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentQuoteReaderInterface;

class SalesOrderAmendmentQuoteSaver implements SalesOrderAmendmentQuoteSaverInterface
{
    /**
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Creator\SalesOrderAmendmentQuoteCreatorInterface $salesOrderAmendmentQuoteCreator
     * @param \Spryker\Zed\SalesOrderAmendment\Business\Reader\SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader
     */
    public function __construct(
        protected SalesOrderAmendmentQuoteCreatorInterface $salesOrderAmendmentQuoteCreator,
        protected SalesOrderAmendmentQuoteReaderInterface $salesOrderAmendmentQuoteReader
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveNotExistingSalesOrderAmendmentQuote(QuoteTransfer $quoteTransfer): void
    {
        $salesOrderAmendmentQuoteTransfer = $this->salesOrderAmendmentQuoteReader
            ->findSalesOrderAmendmentQuoteByOrderReference($quoteTransfer->getAmendmentOrderReferenceOrFail());

        if ($salesOrderAmendmentQuoteTransfer) {
            return;
        }

        $salesOrderAmendmentQuoteCollectionRequestTransfer = (new SalesOrderAmendmentQuoteCollectionRequestTransfer())
            ->addSalesOrderAmendmentQuote(
                (new SalesOrderAmendmentQuoteTransfer())
                    ->setQuote($quoteTransfer)
                    ->setCustomerReference($quoteTransfer->getCustomerOrFail()->getCustomerReferenceOrFail())
                    ->setAmendmentOrderReference($quoteTransfer->getAmendmentOrderReferenceOrFail()),
            );

        $this->salesOrderAmendmentQuoteCreator
            ->createSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);
    }
}
