<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 */
class OriginalOrderBundleItemCartPreReorderPlugin extends AbstractPlugin implements CartPreReorderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Requires `CartReorderTransfer.bundleItems.sku` to be set.
     * - Requires `CartReorderTransfer.bundleItems.quantity` to be set.
     * - Expands `CartReorderTransfer.quote` with original sales order items from the provided `CartReorderTransfer.order.bundleItems`.
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
        foreach ($cartReorderTransfer->getOrderOrFail()->getBundleItems() as $itemTransfer) {
            $cartReorderTransfer->getQuoteOrFail()->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())
                    ->setGroupKey($itemTransfer->getSkuOrFail())
                    ->setSku($itemTransfer->getSkuOrFail())
                    ->setQuantity($itemTransfer->getQuantityOrFail()),
            );
        }

        return $cartReorderTransfer;
    }
}
