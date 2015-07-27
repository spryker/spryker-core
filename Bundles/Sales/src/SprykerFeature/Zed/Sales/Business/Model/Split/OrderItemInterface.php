<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Sales\Business\Model\Split;

use Generated\Shared\Sales\ItemSplitResponseInterface;

interface OrderItemInterface
{
    /**
     * @param integer $orderItemId
     * @param integer $quantityToSplit
     *
     * @return ItemSplitResponseInterface
     * @throws \Exception
     */
    public function split($orderItemId, $quantityToSplit);
}
