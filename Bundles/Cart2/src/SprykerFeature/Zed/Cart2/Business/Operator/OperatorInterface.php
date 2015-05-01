<?php
namespace SprykerFeature\Zed\Cart2\Business\Operator;

use SprykerFeature\Shared\Cart2\Transfer\CartChangeInterface;
use SprykerFeature\Shared\Cart2\Transfer\CartInterface;

interface OperatorInterface
{
    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function executeOperation(CartChangeInterface $cartChange);
}