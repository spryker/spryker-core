<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart\Session;

use Generated\Shared\Transfer\CartTransfer;

interface CartSessionInterface
{

    /**
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function getCart();

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cartTransfer
     *
     * @return self
     */
    public function setCart(CartTransfer $cartTransfer);

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
