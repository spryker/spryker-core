<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ChangeInterface;
use Generated\Shared\Cart\GroupKeyParameterInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Cart\Business\Model\CalculableContainer;


/**
 * @method CartDependencyContainer getDependencyContainer()
 */
class CartFacade extends AbstractFacade
{
    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function addToCart(ChangeInterface $cartChange)
    {
        $addOperator = $this->getDependencyContainer()->createAddOperator();

        return $addOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function increaseQuantity(ChangeInterface $cartChange)
    {
        $increaseOperator = $this->getDependencyContainer()->createIncreaseOperator();

        return $increaseOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function removeFromCart(ChangeInterface $cartChange)
    {
        $removeOperator = $this->getDependencyContainer()->createRemoveOperator();

        return $removeOperator->executeOperation($cartChange);
    }

    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function decreaseQuantity(ChangeInterface $cartChange)
    {
        $decreaseOperator = $this->getDependencyContainer()->createDecreaseOperator();

        return $decreaseOperator->executeOperation($cartChange);
    }

    /**
     *
     * @todo call calculator client from cart client.
     * @param CartInterface $cart
     *
     * @return CartInterface
     */
    public function recalculate(CartInterface $cart)
    {
        $calculator = $this->getDependencyContainer()->createCartCalculator();
        $calculableContainer = $calculator->recalculate(new CalculableContainer($cart));

        return $calculableContainer->getCalculableObject();
    }
}
