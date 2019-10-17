<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig getConfig()
 */
class SalesOrderThresholdExpenseSavePlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Persists the threshold expenses of the quote in the database.
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
        $this->getFacade()->saveSalesOrderSalesOrderThresholdExpense($quoteTransfer, $saveOrderTransfer);
    }
}
