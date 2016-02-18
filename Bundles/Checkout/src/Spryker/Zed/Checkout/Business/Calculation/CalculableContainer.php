<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\Calculation;

use Generated\Shared\Transfer\CartTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class CalculableContainer implements CalculableInterface
{

    /**
     * @var \Generated\Shared\Transfer\CartTransfer
     */
    private $cart;

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     */
    public function __construct(CartTransfer $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return \Generated\Shared\Transfer\CalculableContainerTransfer
     */
    public function getCalculableObject()
    {
        return $this->cart;
    }

}
