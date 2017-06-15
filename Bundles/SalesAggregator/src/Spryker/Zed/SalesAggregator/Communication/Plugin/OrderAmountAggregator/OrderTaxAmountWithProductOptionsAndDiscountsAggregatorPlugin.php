<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Communication\Plugin\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface;

/**
 * @method \Spryker\Zed\SalesAggregator\Business\SalesAggregatorFacade getFacade()
 * @method \Spryker\Zed\SalesAggregator\Communication\SalesAggregatorCommunicationFactory getFactory()
 */
class OrderTaxAmountWithProductOptionsAndDiscountsAggregatorPlugin extends AbstractPlugin implements OrderTotalsAggregatePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->getFacade()->aggregateOrderTotalTaxAmountWithDiscounts($orderTransfer);
    }

}
