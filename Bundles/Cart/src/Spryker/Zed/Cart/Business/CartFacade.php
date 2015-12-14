<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Cart\Business\CartBusinessFactory getFactory()
 */
class CartFacade extends AbstractFacade implements CartFacadeInterface
{

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function addToCart(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->add($cartChangeTransfer);
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function increaseQuantity(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->increase($cartChangeTransfer);
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function removeFromCart(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->remove($cartChangeTransfer);
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function decreaseQuantity(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->decrease($cartChangeTransfer);
    }

}
