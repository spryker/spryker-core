<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Communication\Plugin\Sales;

use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\SalesOrderItemCollectionPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\Communication\SalesConfigurableBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 */
class SalesConfiguredBundlesSalesOrderItemCollectionPostUpdatePlugin extends AbstractPlugin implements SalesOrderItemCollectionPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `SalesOrderItemCollectionResponseTransfer.items` to be provided.
     * - Expects `SalesOrderItemCollectionResponseTransfer.items.configuredBundleItem` to be provided.
     * - Expects `SalesOrderItemCollectionResponseTransfer.items.configuredBundle` to be provided.
     * - Requires `SalesOrderItemCollectionResponseTransfer.items.idSalesOrderItem` to be set.
     * - Updates configured bundle item from `ItemTransfer` to `spy_sales_order_configured_bundle_item` table.
     * - Updates configured bundle from `ItemTransfer` to `spy_sales_order_configured_bundle` table.
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
            ->createSalesOrderConfiguredBundleWriter()
            ->updateSalesOrderConfiguredBundles($salesOrderItemCollectionResponseTransfer);
    }
}
