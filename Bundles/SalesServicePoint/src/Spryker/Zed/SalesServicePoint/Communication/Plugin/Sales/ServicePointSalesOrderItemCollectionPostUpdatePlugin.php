<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Communication\Plugin\Sales;

use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\SalesServicePoint\Business\SalesServicePointFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesServicePoint\Business\SalesServicePointBusinessFactory getBusinessFactory()
 */
class ServicePointSalesOrderItemCollectionPostUpdatePlugin extends AbstractPlugin implements SalesOrderItemCollectionPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `SalesOrderItemCollectionResponseTransfer.items` to be provided.
     * - Expects `SalesOrderItemCollectionResponseTransfer.items.servicePoint` to be provided.
     * - Requires `SalesOrderItemCollectionResponseTransfer.items.idSalesOrderItem` to be set.
     * - Requires `SalesOrderItemCollectionResponseTransfer.items.servicePoint.name` to be set.
     * - Requires `SalesOrderItemCollectionResponseTransfer.items.servicePoint.key` to be set.
     * - Updates service point information from `ItemTransfer` to `spy_sales_order_item_service_point` table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function postUpdate(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        return $this->getBusinessFactory()
            ->createSalesOrderItemServicePointsSaver()
            ->updateSalesOrderItemServicePoints($salesOrderItemCollectionResponseTransfer);
    }
}
