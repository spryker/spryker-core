<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;

interface DiscountableItemTransformerStrategyPluginInterface
{
    /**
     * Specification:
     * - Returns true if strategy can be used for the item.
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
     * - Transforms discountable item according to selected strategy.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
     *
     * @return void
     */
    public function transformDiscountableItem(
        DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
    ): void;
}
