<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountExtension\Dependency\Plugin\Distributor;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountTransfer;

interface DiscountableItemExpanderStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if plugin is can be used for the item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     *
     * @return bool
     */
    public function isApplicable(DiscountableItemTransfer $discountableItemTransfer): bool;

    /**
     * Specification:
     * - Expands
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer $discountableItemTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param int $totalDiscountAmount
     * @param int $totalAmount
     * @param int $quantity
     *
     * @return void
     */
    public function expandDiscountableItem(DiscountableItemTransfer $discountableItemTransfer, DiscountTransfer $discountTransfer, $totalDiscountAmount, $totalAmount, $quantity);
}
