<?php
namespace SprykerFeature\Zed\Cart2\Business\Operator;

use Generated\Shared\Transfer\Cart2CartChangeInterfaceTransfer;
use Generated\Shared\Transfer\Cart2CartInterfaceTransfer;

interface OperatorInterface
{
    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function executeOperation(CartChangeInterface $cartChange);
}
