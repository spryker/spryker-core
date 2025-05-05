<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuoteRequestConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesQuoteRequestConnector\Persistence\SalesQuoteRequestConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesQuoteRequestConnector\Business\SalesQuoteRequestConnectorBusinessFactory getFactory()
 */
class SalesQuoteRequestConnectorFacade extends AbstractFacade implements SalesQuoteRequestConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
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
    ): void {
        $this->getFactory()
            ->createSalesQuoteRequestWriter()
            ->saveOrderQuoteRequestVersionReference($quoteTransfer, $saveOrderTransfer);
    }
}
