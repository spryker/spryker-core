<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOption\Communication\ProductOptionCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 */
class ProductOptionsOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands order items with product options.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemTransfers): array
    {
        return $this->getFacade()->expandOrderItemsWithProductOptions($itemTransfers);
    }
}
