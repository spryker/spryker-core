<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Business\Calculation;

use Generated\Shared\Checkout\CartInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use Generated\Shared\Calculation\CalculableContainerInterface;

class CalculableContainer implements CalculableInterface
{

    /**
     * @var CartInterface
     */
    private $cart;

    /**
     * @param CartInterface $cart
     */
    public function __construct(CartInterface $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return CalculableContainerInterface
     */
    public function getCalculableObject()
    {
        return $this->cart;
    }

}
