<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Cart\Business\Model\CalculableContainer;

/**
 * @method CartBusinessFactory getBusinessFactory()
 */
class CartFacade extends AbstractFacade
{

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function addToCart(ChangeTransfer $cartChange)
    {
        $addOperator = $this->getBusinessFactory()->createAddOperator();

        return $addOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function increaseQuantity(ChangeTransfer $cartChange)
    {
        $increaseOperator = $this->getBusinessFactory()->createIncreaseOperator();

        return $increaseOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function removeFromCart(ChangeTransfer $cartChange)
    {
        $removeOperator = $this->getBusinessFactory()->createRemoveOperator();

        return $removeOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function decreaseQuantity(ChangeTransfer $cartChange)
    {
        $decreaseOperator = $this->getBusinessFactory()->createDecreaseOperator();

        return $decreaseOperator->executeOperation($cartChange);
    }

    /**
     * @todo call calculator client from cart client.
     *
     * @param CartTransfer $cart
     *
     * @return CartTransfer
     */
    public function recalculate(CartTransfer $cart)
    {
        $calculator = $this->getBusinessFactory()->createCartCalculator();
        $calculableContainer = $calculator->recalculate(new CalculableContainer($cart));

        return $calculableContainer->getCalculableObject();
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function addCouponCode(ChangeTransfer $cartChange)
    {
        $addCouponCodeOperator = $this->getBusinessFactory()->createCouponCodeAddOperator();

        return $addCouponCodeOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function removeCouponCode(ChangeTransfer $cartChange)
    {
        $removeCouponCodeOperator = $this->getBusinessFactory()->createCouponCodeRemoveOperator();

        return $removeCouponCodeOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function clearCouponCodes(ChangeTransfer $cartChange)
    {
        $clearCouponCodesOperator = $this->getBusinessFactory()->createCouponCodeClearOperator();

        return $clearCouponCodesOperator->executeOperation($cartChange);
    }

}
