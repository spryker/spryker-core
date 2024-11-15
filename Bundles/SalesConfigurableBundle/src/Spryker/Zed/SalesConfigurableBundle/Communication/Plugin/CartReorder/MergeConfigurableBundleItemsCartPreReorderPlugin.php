<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesConfigurableBundle\Communication\SalesConfigurableBundleCommunicationFactory getFactory()
 */
class MergeConfigurableBundleItemsCartPreReorderPlugin extends AbstractPlugin implements CartPreReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.order.items.groupKey` to be set.
     * - Requires `CartReorderTransfer.order.items.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.order.items.quantity` to be set.
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.order.items` that have `ItemTransfer.salesOrderConfiguredBundle` and `ItemTransfer.salesOrderConfiguredBundleItem` set.
     * - Filters extracted items by `CartReorderRequestTransfer.salesOrderItemIds`.
     * - Merges extracted items and configured bundles quantity by `ItemTransfer.groupKey`.
     * - Replaces `CartReorderTransfer.orderItems` with merged items by `idSalesOrderItem`.
     * - Returns `CartReorderTransfer` with merged order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function preReorder(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderTransfer $cartReorderTransfer
    ): CartReorderTransfer {
        return $this->getFacade()->mergeConfigurableBundleProductsCartReorderItems(
            $cartReorderRequestTransfer,
            $cartReorderTransfer,
        );
    }
}
