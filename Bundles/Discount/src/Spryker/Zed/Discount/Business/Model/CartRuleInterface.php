<?php

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\CartRuleTransfer;
use Generated\Shared\Transfer\DiscountTransfer;

interface CartRuleInterface
{

    /**
     * @param int $idDiscount
     *
     * @return array
     */
    public function getCurrentCartRulesDetailsByIdDiscount($idDiscount);

    /**
     * @param \Generated\Shared\Transfer\CartRuleTransfer $cartRuleFormTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function saveCartRule(CartRuleTransfer $cartRuleFormTransfer);

}
