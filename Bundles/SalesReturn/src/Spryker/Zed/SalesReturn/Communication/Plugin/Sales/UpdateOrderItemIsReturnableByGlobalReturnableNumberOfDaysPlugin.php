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
class UpdateOrderItemIsReturnableByGlobalReturnableNumberOfDaysPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Verifies difference between order item creation date and config const.
     * - If difference more than config const, sets `Item::isReturnable=false`.
     * - Adds return policy message to `Item::returnPolicyMessages`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemTransfers): array
    {
        return $this->getFacade()->setOrderItemIsReturnableByGlobalReturnableNumberOfDays($itemTransfers);
    }
}
