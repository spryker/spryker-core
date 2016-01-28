<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Sales\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderAmountAggregatorInterface
{
    /**
     * @param OrderTransfer $orderTransfer
     */
    public function aggregate(OrderTransfer $orderTransfer);
}
