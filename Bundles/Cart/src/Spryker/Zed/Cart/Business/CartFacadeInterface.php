<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Cart\Business;

use Generated\Shared\Transfer\CartChangeTransfer;

/**
 * @method \Spryker\Zed\Cart\Business\CartBusinessFactory getFactory()
 */
interface CartFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToCart(CartChangeTransfer $cartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseQuantity(CartChangeTransfer $cartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeFromCart(CartChangeTransfer $cartChangeTransfer);

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseQuantity(CartChangeTransfer $cartChangeTransfer);

}
