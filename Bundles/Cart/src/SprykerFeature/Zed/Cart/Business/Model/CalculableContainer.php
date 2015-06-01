<?php

namespace SprykerFeature\Zed\Cart\Business\Model;

use Generated\Shared\Calculation\CalculableContainerInterface;
use Generated\Shared\Cart\CartInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

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
