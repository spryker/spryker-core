<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin\Sales;

use Generated\Shared\Transfer\SalesOrderItemCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPreDeletePluginInterface;

/**
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 */
class GiftCardSalesOrderItemCollectionPreDeletePlugin extends AbstractPlugin implements SalesOrderItemCollectionPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Uses `SalesOrderItemCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter sales order item gift card entities by the sales order item IDs.
     * - Deletes found by criteria sales order item gift card entities.
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
        $this->getFacade()->deleteSalesOrderItemGiftCardCollection(
            (new SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer())->setSalesOrderItemIds(
                $salesOrderItemCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            ),
        );
    }
}
