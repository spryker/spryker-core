<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Plugin\Sales;

use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemOptionCollectionDeleteCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 */
class ProductOptionSalesOrderItemCollectionPreDeletePlugin extends AbstractPlugin implements SalesOrderItemCollectionPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Uses `SalesOrderItemCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter sales order item option entities by the sales order item IDs.
     * - Deletes found by criteria sales order item option entities.
     * - Does nothing if no criteria properties are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function preDelete(
        SalesOrderItemCollectionDeleteCriteriaTransfer $salesOrderItemCollectionDeleteCriteriaTransfer
    ): void {
        $this->getFacade()->deleteSalesOrderItemOptionCollection(
            (new SalesOrderItemOptionCollectionDeleteCriteriaTransfer())->setSalesOrderItemIds(
                $salesOrderItemCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            ),
        );
    }
}
