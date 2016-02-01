<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business\Model;

use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Generated\Shared\Transfer\CalculableContainerTransfer;
use Generated\Shared\Transfer\CartTransfer;

class CalculableContainer implements CalculableInterface
{

    /**
     * @var CartTransfer
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
