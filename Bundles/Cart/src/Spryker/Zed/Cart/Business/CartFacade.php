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
 * @method CartDependencyContainer getDependencyContainer()
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
        $addOperator = $this->getDependencyContainer()->createAddOperator();

        return $addOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function increaseQuantity(ChangeTransfer $cartChange)
    {
        $increaseOperator = $this->getDependencyContainer()->createIncreaseOperator();

        return $increaseOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function removeFromCart(ChangeTransfer $cartChange)
    {
        $removeOperator = $this->getDependencyContainer()->createRemoveOperator();

        return $removeOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function decreaseQuantity(ChangeTransfer $cartChange)
    {
        $decreaseOperator = $this->getDependencyContainer()->createDecreaseOperator();

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
        $calculator = $this->getDependencyContainer()->createCartCalculator();
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
        $addCouponCodeOperator = $this->getDependencyContainer()->createCouponCodeAddOperator();

        return $addCouponCodeOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function removeCouponCode(ChangeTransfer $cartChange)
    {
        $removeCouponCodeOperator = $this->getDependencyContainer()->createCouponCodeRemoveOperator();

        return $removeCouponCodeOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeTransfer $cartChange
     *
     * @return CartTransfer
     */
    public function clearCouponCodes(ChangeTransfer $cartChange)
    {
        $clearCouponCodesOperator = $this->getDependencyContainer()->createCouponCodeClearOperator();

        return $clearCouponCodesOperator->executeOperation($cartChange);
    }

}
