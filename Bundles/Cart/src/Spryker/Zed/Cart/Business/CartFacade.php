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
 * @method \Spryker\Zed\Cart\Business\CartBusinessFactory getFactory()
 */
class CartFacade extends AbstractFacade
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addToCart(ChangeTransfer $cartChange)
    {
        $addOperator = $this->getFactory()->createAddOperator();

        return $addOperator->executeOperation($cartChange);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function increaseQuantity(ChangeTransfer $cartChange)
    {
        $increaseOperator = $this->getFactory()->createIncreaseOperator();

        return $increaseOperator->executeOperation($cartChange);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeFromCart(ChangeTransfer $cartChange)
    {
        $removeOperator = $this->getFactory()->createRemoveOperator();

        return $removeOperator->executeOperation($cartChange);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function decreaseQuantity(ChangeTransfer $cartChange)
    {
        $decreaseOperator = $this->getFactory()->createDecreaseOperator();

        return $decreaseOperator->executeOperation($cartChange);
    }

    /**
     * @todo call calculator client from cart client.
     *
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function recalculate(CartTransfer $cart)
    {
        $calculator = $this->getFactory()->getCartCalculator();
        $calculableContainer = $calculator->recalculate(new CalculableContainer($cart));

        return $calculableContainer->getCalculableObject();
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function addCouponCode(ChangeTransfer $cartChange)
    {
        $addCouponCodeOperator = $this->getFactory()->createCouponCodeAddOperator();

        return $addCouponCodeOperator->executeOperation($cartChange);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function removeCouponCode(ChangeTransfer $cartChange)
    {
        $removeCouponCodeOperator = $this->getFactory()->createCouponCodeRemoveOperator();

        return $removeCouponCodeOperator->executeOperation($cartChange);
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function clearCouponCodes(ChangeTransfer $cartChange)
    {
        $clearCouponCodesOperator = $this->getFactory()->createCouponCodeClearOperator();

        return $clearCouponCodesOperator->executeOperation($cartChange);
    }

}
