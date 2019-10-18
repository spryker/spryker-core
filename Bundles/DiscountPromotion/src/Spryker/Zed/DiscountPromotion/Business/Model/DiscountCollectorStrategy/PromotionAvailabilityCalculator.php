<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface;

class PromotionAvailabilityCalculator implements PromotionAvailabilityCalculatorInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface $availabilityFacade
     */
    public function __construct(
        DiscountPromotionToAvailabilityInterface $availabilityFacade
    ) {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param string $sku
     * @param int $maxQuantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getMaximumQuantityBasedOnAvailability(string $sku, int $maxQuantity, StoreTransfer $storeTransfer): int
    {
        $productAbstractAvailabilityTransfer = $this->availabilityFacade
            ->findProductAbstractAvailabilityBySkuForStore($sku, $storeTransfer);

        if ($productAbstractAvailabilityTransfer === null) {
            return 0;
        }

        if ($productAbstractAvailabilityTransfer->getIsNeverOutOfStock()) {
            return $maxQuantity;
        }

        if ($productAbstractAvailabilityTransfer->getAvailability()->lessThanOrEquals(0)) {
            return 0;
        }

        if ($productAbstractAvailabilityTransfer->getAvailability()->lessThan($maxQuantity)) {
            return $productAbstractAvailabilityTransfer->getAvailability()->toInt();
        }

        return $maxQuantity;
    }
}
