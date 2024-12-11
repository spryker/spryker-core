<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\CartReorder;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderOrderItemFilterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class ProductBundleCartReorderOrderItemFilterPlugin extends AbstractPlugin implements CartReorderOrderItemFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `CartReorderRequestTransfer.bundleItemIdentifiers` to be set.
     * - Requires `CartReorderRequestTransfer.order.items.idSalesOrderItem` to be set.
     * - Filters reorder product bundle items.
     * - Expands bundle items with first `salesOrderItemId` that belongs to the product bundle.
     * - Returns only the filtered bundle items if `CartReorderRequestTransfer.salesOrderItemIds` is empty.
     * - Appends the filtered bundle items to the provided `filteredItems`.
     *
     * @api
     *
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $filteredItems
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function filter(ArrayObject $filteredItems, CartReorderRequestTransfer $cartReorderRequestTransfer): ArrayObject
    {
        $filteredBundleItems = $this->getFacade()->filterReorderBundleItems($cartReorderRequestTransfer, $cartReorderRequestTransfer->getOrderOrFail());

        if ($filteredBundleItems->count() && $cartReorderRequestTransfer->getSalesOrderItemIds() === []) {
            return $filteredBundleItems;
        }

        foreach ($filteredBundleItems as $filteredBundleItem) {
            $filteredItems->append($filteredBundleItem);
        }

        return $filteredItems;
    }
}
