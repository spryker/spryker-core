<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 */
class SalesOrderAmendmentItemsCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.originalOrder` to be set.
     * - Obtains order from `QuoteTransfer.originalOrder, compares the items with the provided quote items and replaces the items if they differ.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentItemCollectorStrategyPluginInterface} plugins
     * to identify a strategy to divide order items into groups to create/update/delete/skip.
     * - Uses default strategy if no applicable strategy is found.
     * - Uses {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::createSalesOrderItemCollectionByQuote()} to create new order items.
     * - Uses {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::updateSalesOrderItemCollectionByQuote()} to update existing order items.
     * - Uses {@link \Spryker\Zed\Sales\Business\SalesFacadeInterface::deleteSalesOrderItemCollection()} to delete order items.
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
        $this->getFacade()->replaceSalesOrderItems($quoteTransfer, $saveOrderTransfer);
    }
}
