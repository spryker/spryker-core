<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model;

use Generated\Shared\Transfer\DiscountPromotionTransfer;

interface DiscountPromotionWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function create(DiscountPromotionTransfer $discountPromotionTransfer);

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function update(DiscountPromotionTransfer $discountPromotionTransfer);

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return int
     */
    public function removePromotionFromDiscount(DiscountPromotionTransfer $discountPromotionTransfer): int;
}
