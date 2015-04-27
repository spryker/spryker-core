<?php
namespace SprykerFeature\Zed\Cart\Business\Operator;

use SprykerFeature\Shared\Cart\Transfer\CartChangeInterface;
use SprykerFeature\Shared\Cart\Transfer\CartInterface;

interface OperatorInterface
{
    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function executeOperation(CartChangeInterface $cartChange);
}