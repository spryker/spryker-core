<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesServicePoint\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SalesServicePoint\Communication\SalesServicePointCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesServicePoint\Business\SalesServicePointFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesServicePoint\SalesServicePointConfig getConfig()
 */
class ServicePointOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ItemTransfer.idSalesOrderItem` property to be set.
     * - Expands `ItemTransfer` with `SalesOrderItemServicePointTransfer` if service point is available.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expand(array $itemTransfers): array
    {
        return $this->getFacade()->expandOrderItemsWithServicePoint($itemTransfers);
    }
}
