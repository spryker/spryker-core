<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy;

interface PromotionAvailabilityCalculatorInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $maxQuantity
     *
     * @return int
     */
    public function getMaximumQuantityBasedOnAvailability(int $idProductAbstract, int $maxQuantity): int;
}
