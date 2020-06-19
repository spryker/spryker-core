<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderListExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 */
class OrderStateDisplayNamesOrderListExpanderPlugin extends AbstractPlugin implements OrderListExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands orders with a list of unique display state names from order items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function expand(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderTransfers = $this->getFacade()->expandOrdersWithItemStateDisplayNames(
            $orderListTransfer->getOrders()->getArrayCopy()
        );

        $orderListTransfer->getOrders()->exchangeArray($orderTransfers);

        return $orderListTransfer;
    }
}
