<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface;

/**
 * @method \Spryker\Zed\SalesOms\SalesOmsConfig getConfig()
 * @method \Spryker\Zed\SalesOms\Business\SalesOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOms\Communication\SalesOmsCommunicationFactory getFactory()
 */
class OrderItemReferenceExpanderPreSavePlugin extends AbstractPlugin implements OrderItemExpanderPreSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds merchant order reference to sales order item before saving
     * - If there is no fkMerchant in ItemTransfer, returns $salesOrderItemEntity without changes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItem(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
    ): SpySalesOrderItemEntityTransfer {
        return $this->getFacade()->expandOrderItemWithReference($salesOrderItemEntityTransfer, $itemTransfer);
    }
}
