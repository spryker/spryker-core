<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Checker;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface DiscountPromotionItemCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param string $abstractSku
     *
     * @return bool
     */
    public function isItemRelatedToDiscountPromotion(
        ItemTransfer $itemTransfer,
        DiscountPromotionTransfer $discountPromotionTransfer,
        string $abstractSku
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isItemPromotional(
        DiscountPromotionTransfer $discountPromotionTransfer,
        ItemTransfer $itemTransfer
    ): bool;
}
