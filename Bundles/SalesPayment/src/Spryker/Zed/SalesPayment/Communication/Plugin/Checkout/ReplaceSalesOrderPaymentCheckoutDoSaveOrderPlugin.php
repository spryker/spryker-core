<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesPayment\SalesPaymentConfig getConfig()
 * @method \Spryker\Zed\SalesPayment\Business\SalesPaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesPayment\Communication\SalesPaymentCommunicationFactory getFactory()
 */
class ReplaceSalesOrderPaymentCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SaveOrderTransfer.idSalesOrder` to be set.
     * - Deletes all sales payment entities related to the provided order.
     * - Executes {@link \Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\SalesPaymentPreDeletePluginInterface} plugin stack before the sales payment entities deletion.
     * - Saves order payments from `QuoteTransfer`.
     * - Executes {@link \Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->getFacade()->replaceSalesPayments($quoteTransfer, $saveOrderTransfer);
    }
}
