<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountableItemCreatorInterface
{
    /**
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer|null
     */
    public function createDiscountableItemBySku(
        string $abstractSku,
        QuoteTransfer $quoteTransfer,
        DiscountPromotionTransfer $discountPromotionTransfer,
        DiscountTransfer $discountTransfer
    ): ?DiscountableItemTransfer;
}
