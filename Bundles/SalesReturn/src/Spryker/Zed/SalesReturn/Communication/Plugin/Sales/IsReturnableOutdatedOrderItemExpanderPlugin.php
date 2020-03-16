<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesReturn\SalesReturnConfig getConfig()
 */
class IsReturnableOutdatedOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets `Item::isReturnable=false` for outdated order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemTransfers): array
    {
        return $this->getFacade()->expandOutdatedOrderItemsWithIsReturnable($itemTransfers);
    }
}
