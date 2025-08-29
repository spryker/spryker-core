<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 */
class UpdateOrderByQuoteCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.originalOrder` to be set.
     * - Requires `QuoteTransfer.customer` to be set.
     * - Requires `QuoteTransfer.customer.customerReference` to be set.
     * - Requires `QuoteTransfer.currency` to be set.
     * - Requires `QuoteTransfer.currency.code` to be set.
     * - Requires `QuoteTransfer.priceMode` to be set.
     * - Requires `QuoteTransfer.store` to be set.
     * - Requires `QuoteTransfer.store.name` to be set.
     * - Requires `QuoteTransfer.billingAddress` to be set.
     * - Requires `QuoteTransfer.billingAddress.iso2Code` to be set.
     * - Requires `QuoteTransfer.originalOrder.billingAddress` to be set.
     * - Requires `QuoteTransfer.originalOrder.billingAddress.idSalesOrderAddress` to be set.
     * - Updates order billing address with billing address data from quote.
     * - BC: Updates order shipping address with shipping address data from quote.
     * - BC: Hydrates quote items with shipping address.
     * - Resolves a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPreSavePluginInterface} according to the quote process flow.
     * - Executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPreSavePluginInterface}.
     * - Updates order data with data from quote.
     * - Sets the current locale ID to the order.
     * - Resolves a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface} according to the quote process flow.
     * - Executes a stack of {@link \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface}.
     * - Maps updated `OrderTransfer` to `SaveOrderTransfer`.
     * - Returns `SaveOrderTransfer` with updated order data.
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
        $this->getFacade()->updateOrderByQuote($quoteTransfer, $saveOrderTransfer);
    }
}
