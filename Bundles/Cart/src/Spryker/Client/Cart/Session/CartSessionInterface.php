<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @return $this
     */
    public function setCart(CartTransfer $cartTransfer);

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param int $itemCount
     *
     * @return $this
     */
    public function setItemCount($itemCount);

}
