<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountSalesAggregatorConnector\Communication\Plugin\SalesAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface;

/**
 * @method \Spryker\Zed\DiscountSalesAggregatorConnector\Business\DiscountSalesAggregatorConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountSalesAggregatorConnector\Communication\DiscountSalesAggregatorConnectorCommunicationFactory getFactory()
 */
class DiscountTotalAmountAggregatorPlugin extends AbstractPlugin implements OrderTotalsAggregatePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->getFacade()->aggregateOrderTotalDiscountAmount($orderTransfer);
    }

}
