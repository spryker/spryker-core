<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Communication\Controller;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Cart\Business\CartFacade;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method CartFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addItemAction(ChangeTransfer $cartChange)
    {
        return $this->getFacade()->addToCart($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseItemQuantityAction(ChangeTransfer $cartChange)
    {
        return $this->getFacade()->increaseQuantity($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseItemQuantityAction(ChangeTransfer $cartChange)
    {
        return $this->getFacade()->decreaseQuantity($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeItemAction(ChangeTransfer $cartChange)
    {
        return $this->getFacade()->removeFromCart($cartChange);
    }

    /**
     * @param CartTransfer $cartTransfer
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculateAction(CartTransfer $cartTransfer)
    {
        return $this->getFacade()->recalculate($cartTransfer);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCouponCodeAction(ChangeTransfer $cartChange)
    {
        return $this->getFacade()->addCouponCode($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCouponCodeAction(ChangeTransfer $cartChange)
    {
        return $this->getFacade()->removeCouponCode($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCouponCodesAction(ChangeTransfer $cartChange)
    {
        return $this->getFacade()->clearCouponCodes($cartChange);
    }

}
