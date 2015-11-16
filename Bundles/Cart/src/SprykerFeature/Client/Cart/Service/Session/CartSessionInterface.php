<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cart\Service\Session;

use Generated\Shared\Cart\CartInterface;

interface CartSessionInterface
{

    /**
     * @return CartInterface
     */
    public function getCart();

    /**
     * @param CartInterface $cartTransfer
     *
     * @return self
     */
    public function setCart(CartInterface $cartTransfer);

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param $itemCount
     *
     * @return self
     */
    public function setItemCount($itemCount);

}
