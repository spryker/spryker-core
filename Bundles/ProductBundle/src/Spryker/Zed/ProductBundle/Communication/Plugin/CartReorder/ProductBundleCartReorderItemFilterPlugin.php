<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\CartReorder;

use ArrayObject;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemFilterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class ProductBundleCartReorderItemFilterPlugin extends AbstractPlugin implements CartReorderItemFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `CartReorderRequestTransfer.bundleItemIdentifiers` to be set.
     * - Requires `OrderTransfer.items.idSalesOrderItem` to be set.
     * - Filters reorder product bundle items.
     * - Expands bundle items with first `salesOrderItemId` that belongs to the product bundle.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function filter(CartReorderRequestTransfer $cartReorderRequestTransfer, OrderTransfer $orderTransfer): ArrayObject
    {
        return $this->getFacade()->filterReorderBundleItems($cartReorderRequestTransfer, $orderTransfer);
    }
}
