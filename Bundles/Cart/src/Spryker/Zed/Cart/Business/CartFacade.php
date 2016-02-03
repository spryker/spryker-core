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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToCart(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->add($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseQuantity(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->increase($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeFromCart(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->remove($cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseQuantity(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createCartOperation()->decrease($cartChangeTransfer);
    }

}
