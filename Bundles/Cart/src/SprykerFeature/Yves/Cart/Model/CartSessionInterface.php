<?php

namespace SprykerFeature\Yves\Cart\Model;

use Generated\Shared\Transfer\OrderTransfer;

interface CartSessionInterface
{
    /**
     * @return OrderTransfer
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
     * @param OrderTransfer $order
     * @return $this
     */
    public function setOrder(OrderTransfer $order);

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getQuantityBySku($sku);
}
