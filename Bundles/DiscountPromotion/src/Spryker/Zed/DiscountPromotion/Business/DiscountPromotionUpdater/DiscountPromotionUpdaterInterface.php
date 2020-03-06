<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\DiscountPromotionUpdater;

use Generated\Shared\Transfer\DiscountPromotionTransfer;

interface DiscountPromotionUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function update(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer;
}
