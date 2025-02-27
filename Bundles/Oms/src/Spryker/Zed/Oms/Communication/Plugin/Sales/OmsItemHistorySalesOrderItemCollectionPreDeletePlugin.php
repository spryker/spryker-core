<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OmsEventTimeoutCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\OmsTransitionLogCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface;

/**
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 */
class OmsItemHistorySalesOrderItemCollectionPreDeletePlugin extends AbstractPlugin implements SalesOrderItemCollectionPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Uses `OmsEventTimeoutCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter OMS event timeout entities by the sales order item IDs.
     * - Uses `OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter OMS order item state history entities by the sales order item IDs.
     * - Uses `OmsTransitionLogCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter OMS transition log entities by the sales order item IDs.
     * - Deletes found by criteria entities.
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
        $salesOrderItemIds = $salesOrderItemCollectionDeleteCriteriaTransfer->getSalesOrderItemIds();

        if ($salesOrderItemIds) {
            $this->getFacade()->deleteOmsEventTimeoutCollection(
                (new OmsEventTimeoutCollectionDeleteCriteriaTransfer())->setSalesOrderItemIds($salesOrderItemIds),
            );
            $this->getFacade()->deleteOmsOrderItemStateHistoryCollection(
                (new OmsOrderItemStateHistoryCollectionDeleteCriteriaTransfer())->setSalesOrderItemIds($salesOrderItemIds),
            );
            $this->getFacade()->deleteOmsTransitionLogCollection(
                (new OmsTransitionLogCollectionDeleteCriteriaTransfer())->setSalesOrderItemIds($salesOrderItemIds),
            );
        }
    }
}
