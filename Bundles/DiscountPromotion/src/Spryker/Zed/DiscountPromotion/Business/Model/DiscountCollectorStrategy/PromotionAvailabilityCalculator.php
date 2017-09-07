<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy;

use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleInterface;

class PromotionAvailabilityCalculator implements PromotionAvailabilityCalculatorInterface
{

    /**
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleInterface $localeFacade
     */
    public function __construct(
        DiscountPromotionToAvailabilityInterface $availabilityFacade,
        DiscountPromotionToLocaleInterface $localeFacade
    ) {

        $this->availabilityFacade = $availabilityFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param string $promotionProductAbstractSku
     * @param int $maxQuantity
     *
     * @return int
     */
    public function getMaximumQuantityBasedOnAvailability($promotionProductAbstractSku, $maxQuantity)
    {
        $productAbstractAvailabilityTransfer = $this->getProductAbstractAvailability($promotionProductAbstractSku);

        if ($productAbstractAvailabilityTransfer->getIsNeverOutOfStock()) {
            return $maxQuantity;
        }

        if ($productAbstractAvailabilityTransfer->getAvailability() <= 0) {
            return 0;
        }

        if ($maxQuantity > $productAbstractAvailabilityTransfer->getAvailability()) {
            return $productAbstractAvailabilityTransfer->getAvailability();
        }

        return $maxQuantity;
    }

    /**
     * @param string $promotionProductAbstractSku
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    protected function getProductAbstractAvailability($promotionProductAbstractSku)
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $productAbstractAvailabilityTransfer = $this->availabilityFacade
            ->getProductAbstractAvailability(
                $promotionProductAbstractSku,
                $localeTransfer->getIdLocale()
            );

        return $productAbstractAvailabilityTransfer;
    }

}
