<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnit\Communication\ProductPackagingUnitCommunicationFactory getFactory()
 */
class MergeProductPackagingUnitItemsCartPreReorderPlugin extends AbstractPlugin implements CartPreReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.order.items.groupKey` to be set.
     * - Requires `CartReorderTransfer.order.items.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.order.items.quantity` to be set.
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.order.items` that have `ItemTransfer.amountSalesUnit` set.
     * - Filters extracted items by `CartReorderRequestTransfer.salesOrderItemIds`.
     * - Merges extracted items' quantity and amount by `ItemTransfer.groupKey`.
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
        return $this->getFacade()->mergeProductPackagingUnitCartReorderItems(
            $cartReorderRequestTransfer,
            $cartReorderTransfer,
        );
    }
}
