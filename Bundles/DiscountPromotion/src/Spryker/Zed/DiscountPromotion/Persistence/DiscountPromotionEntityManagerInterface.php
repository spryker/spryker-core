<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Generated\Shared\Transfer\DiscountPromotionTransfer;

interface DiscountPromotionEntityManagerInterface
{
    /**
     * @param int $idDiscount
     *
     * @return void
     */
    public function removePromotionByIdDiscount(int $idDiscount): void;

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function createDiscountPromotion(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer;

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function updateDiscountPromotion(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer;
}
