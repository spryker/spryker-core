<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\UniqueOrderItemsExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface getQueryContainer()
 */
class UniqueOrderBundleItemsExpanderPlugin extends AbstractPlugin implements UniqueOrderItemsExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes items from array related to bundles.
     * - Expands provided array of ItemTransfers by product bundles.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemsTransfers, OrderTransfer $orderTransfer): array
    {
        return $this->getFacade()->expandUniqueOrderItemsWithProductBundles($itemsTransfers, $orderTransfer);
    }
}
