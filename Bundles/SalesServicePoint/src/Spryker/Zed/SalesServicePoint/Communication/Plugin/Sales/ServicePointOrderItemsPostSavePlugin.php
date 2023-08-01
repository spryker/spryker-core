<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface;

/**
 * @method \Spryker\Zed\SalesServicePoint\Business\SalesServicePointFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesServicePoint\Persistence\SalesServicePointQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesServicePoint\SalesServicePointConfig getConfig()
 */
class ServicePointOrderItemsPostSavePlugin extends AbstractPlugin implements OrderItemsPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Persists service point information from `ItemTransfer` in Quote to `spy_sales_order_item_service_point` table.
     * - Expects service point to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function execute(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer
    ): SaveOrderTransfer {
        $this->getFacade()->saveSalesOrderItemServicePointsFromQuote($quoteTransfer);

        return $saveOrderTransfer;
    }
}
