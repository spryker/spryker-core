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
     * @param float $maxQuantity
     *
     * @return float
     */
    public function getMaximumQuantityBasedOnAvailability($idProductAbstract, $maxQuantity);
}
