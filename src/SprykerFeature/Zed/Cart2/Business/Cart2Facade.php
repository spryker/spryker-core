<?php

namespace SprykerFeature\Zed\Cart2\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Shared\Cart2\Transfer\CartChangeInterface;
use SprykerFeature\Shared\Cart2\Transfer\CartInterface;

/**
 * @method Cart2DependencyContainer getDependencyContainer()
 */
class Cart2Facade extends AbstractFacade
{
    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function addToCart(CartChangeInterface $cartChange)
    {
        $addOperator = $this->getDependencyContainer()->createAddOperator();

        return $addOperator->executeOperation($cartChange);
    }

    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function increaseQuantity(CartChangeInterface $cartChange)
    {
        $increaseOperator = $this->getDependencyContainer()->createIncreaseOperator();

        return $increaseOperator->executeOperation($cartChange);
    }

    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function removeFromCart(CartChangeInterface $cartChange)
    {
        $removeOperator = $this->getDependencyContainer()->createRemoveOperator();

        return $removeOperator->executeOperation($cartChange);
    }

    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function decreaseQuantity(CartChangeInterface $cartChange)
    {
        $decreaseOperator = $this->getDependencyContainer()->createDecreaseOperator();

        return $decreaseOperator->executeOperation($cartChange);
    }

    /**
     * @param CartInterface $cart
     *
     * @return CartInterface
     */
    public function recalculateCart(CartInterface  $cart)
    {
        $calculator = $this->getDependencyContainer()->createCartCalculator();

        return $calculator->recalculate($cart);
    }
}
