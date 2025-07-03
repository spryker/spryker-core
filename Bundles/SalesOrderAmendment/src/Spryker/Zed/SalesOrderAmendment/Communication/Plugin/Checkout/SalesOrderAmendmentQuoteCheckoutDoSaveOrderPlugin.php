<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentBusinessFactory getBusinessFactory()()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 */
class SalesOrderAmendmentQuoteCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.customer` to be set.
     * - Requires `QuoteTransfer.customer.customerReference` to be set.
     * - Requires `QuoteTransfer.amendmentOrderReference` to be set.
     * - Creates a new sales order amendment quote entity based on the provided `QuoteTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesOrderAmendmentQuoteCollectionRequestTransfer = (new SalesOrderAmendmentQuoteCollectionRequestTransfer())
            ->addSalesOrderAmendmentQuote(
                (new SalesOrderAmendmentQuoteTransfer())
                    ->setQuote($quoteTransfer)
                    ->setCustomerReference($quoteTransfer->getCustomerOrFail()->getCustomerReferenceOrFail())
                    ->setAmendmentOrderReference($quoteTransfer->getAmendmentOrderReferenceOrFail()),
            );

        $this->getFacade()->createSalesOrderAmendmentQuoteCollection($salesOrderAmendmentQuoteCollectionRequestTransfer);
    }
}
