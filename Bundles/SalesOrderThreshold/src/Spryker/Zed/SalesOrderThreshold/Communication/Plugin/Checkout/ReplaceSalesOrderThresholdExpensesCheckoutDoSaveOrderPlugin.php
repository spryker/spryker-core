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
 * @method \Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig getConfig()
 * @method \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface getFacade()
 */
class ReplaceSalesOrderThresholdExpensesCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SaveOrderTransfer.idSalesOrder` to be set.
     * - Deletes expenses associated with the `SaveOrderTransfer.idSalesOrder` order ID and the type specified by {@link \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_EXPENSE_TYPE} from the database.
     * - Iterates over `QuoteTransfer.expenses` and stores expenses of the type defined by {@link \Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig::THRESHOLD_EXPENSE_TYPE} in the database.
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
        $this->getFacade()->replaceSalesOrderThresholdExpenses($quoteTransfer, $saveOrderTransfer);
    }
}
