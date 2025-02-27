<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Communication\Plugin\Sales;

use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig getConfig()
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorBusinessFactory getBusinessFactory()
 */
class ItemMetadataSalesOrderItemCollectionPostUpdatePlugin extends AbstractPlugin implements SalesOrderItemCollectionPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `SalesOrderItemCollectionResponseTransfer.items` to be provided.
     * - Requires `SalesOrderItemCollectionResponseTransfer.items.idSalesOrderItem` to be set.
     * - Updates product metadata information (image, super attributes) to `spy_sales_order_item_metadata` table.
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
            ->createItemMetadataSaver()
            ->updateOrderItemMetadata($salesOrderItemCollectionResponseTransfer);
    }
}
