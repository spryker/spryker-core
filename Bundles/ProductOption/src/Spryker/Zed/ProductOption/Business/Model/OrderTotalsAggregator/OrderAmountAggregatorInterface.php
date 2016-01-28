<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\ProductOption\Business\Model\OrderTotalsAggregator;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderAmountAggregatorInterface
{
    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer);
}
