<?php

namespace SprykerFeature\Yves\Cart\Model;

use Generated\Shared\Transfer\OrderTransfer;

interface CartSessionInterface
{
    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function getOrder();

    /**
     * Clear the current cart
     */
    public function clear();

    /**
     * Save a new CartOrder, if you just changed the order from get()
     * there is no need to call set().
     *
     * @param Order $order
     * @return $this
     */
    public function setOrder(Order $order);

    /**
     * @param $sku
     * @return int
     */
    public function getQuantityBySku($sku);
}
