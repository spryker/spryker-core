<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\CartRuleTransfer;

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
