<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Communication\Plugin\Sales;

use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\SalesProductConfiguration\Communication\SalesProductConfigurationCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesProductConfiguration\Business\SalesProductConfigurationBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesProductConfiguration\Business\SalesProductConfigurationFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesProductConfiguration\SalesProductConfigurationConfig getConfig()
 */
class SalesProductConfigurationSalesOrderItemCollectionPostUpdatePlugin extends AbstractPlugin implements SalesOrderItemCollectionPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `SalesOrderItemCollectionResponseTransfer.items` to be provided.
     * - Expects `SalesOrderItemCollectionResponseTransfer.items.productConfigurationInstance` to be provided.
     * - Requires `SalesOrderItemCollectionResponseTransfer.items.idSalesOrderItem` to be set.
     * - Requires `SalesOrderItemCollectionResponseTransfer.items.productConfigurationInstance.configuratorKey` to be set.
     * - Updates product configuration from `ItemTransfer` to `spy_sales_order_item_configuration` table.
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
            ->createSalesOrderItemConfigurationWriter()
            ->updateSalesOrderItemConfigurations($salesOrderItemCollectionResponseTransfer);
    }
}
