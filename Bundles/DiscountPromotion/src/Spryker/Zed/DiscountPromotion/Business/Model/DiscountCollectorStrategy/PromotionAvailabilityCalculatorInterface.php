<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy;

use Generated\Shared\Transfer\StoreTransfer;

interface PromotionAvailabilityCalculatorInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $maxQuantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getMaximumQuantityBasedOnAvailability(int $idProductAbstract, int $maxQuantity, StoreTransfer $storeTransfer): int;
}
