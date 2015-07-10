<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Model;

use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use Generated\Shared\Calculation\CalculableContainerInterface;
use Generated\Shared\Cart\CartInterface;

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
