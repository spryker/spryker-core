<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication\Plugin\Sales;

use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesReclamationItemCollectionDeleteCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface;

/**
 * @method \Spryker\Zed\SalesReclamation\SalesReclamationConfig getConfig()
 * @method \Spryker\Zed\SalesReclamation\Business\SalesReclamationFacadeInterface getFacade()
 */
class SalesReclamationSalesOrderItemCollectionPreDeletePlugin extends AbstractPlugin implements SalesOrderItemCollectionPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Uses `SalesOrderItemCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter sales reclamation item entities by the sales order item IDs.
     * - Deletes found by criteria sales reclamation item entities.
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
        $this->getFacade()->deleteSalesReclamationItemCollection(
            (new SalesReclamationItemCollectionDeleteCriteriaTransfer())->setSalesOrderItemIds(
                $salesOrderItemCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            ),
        );
    }
}
