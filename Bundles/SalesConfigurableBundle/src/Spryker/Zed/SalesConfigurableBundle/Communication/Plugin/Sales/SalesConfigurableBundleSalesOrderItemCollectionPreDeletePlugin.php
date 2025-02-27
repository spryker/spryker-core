<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Communication\Plugin\Sales;

use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesConfigurableBundle\Communication\SalesConfigurableBundleCommunicationFactory getFactory()
 */
class SalesConfigurableBundleSalesOrderItemCollectionPreDeletePlugin extends AbstractPlugin implements SalesOrderItemCollectionPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Uses `SalesOrderItemCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter sales order configured bundle item entities by the sales order item IDs.
     * - Deletes found by criteria sales order configured bundle item entities.
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
        $this->getFacade()->deleteSalesOrderConfiguredBundleItemCollection(
            (new SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer())->setSalesOrderItemIds(
                $salesOrderItemCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            ),
        );
    }
}
