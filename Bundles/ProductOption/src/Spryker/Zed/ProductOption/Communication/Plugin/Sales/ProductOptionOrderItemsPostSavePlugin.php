<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionBusinessFactory getBusinessFactory()
 */
class ProductOptionOrderItemsPostSavePlugin extends AbstractPlugin implements OrderItemsPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `QuoteTransfer.items.productOptions` to be provided.
     * - Requires `QuoteTransfer.items.idSalesOrderItem` to be set.
     * - Creates product option information from `ItemTransfer` to `spy_sales_order_item_option` table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function execute(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        $this->getBusinessFactory()->createPlaceOrderProductOptionOrderSaver()->createSalesOrderItemOptions($quoteTransfer);

        return $saveOrderTransfer;
    }
}
