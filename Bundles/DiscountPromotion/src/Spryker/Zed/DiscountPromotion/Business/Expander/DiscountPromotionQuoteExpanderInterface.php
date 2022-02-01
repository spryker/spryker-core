<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Expander;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountPromotionQuoteExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param int $promotionMaximumQuantity
     *
     * @return void
     */
    public function expandWithPromotionItem(
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer,
        DiscountPromotionTransfer $discountPromotionTransfer,
        int $promotionMaximumQuantity
    ): void;
}
