<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;

interface DiscountAmountCalculatorPluginInterface
{
    /**
     * Specification:
     * - Calculate discount amount on given discountable items, which are collected by configured Collectors.
     * - The discount amount is set in DiscountTransfer::amount
     * - Returns calculated amount.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer$discountTransfer
     *
     * @return int
     */
    public function calculateDiscount(array $discountableItems, DiscountTransfer $discountTransfer);
}
