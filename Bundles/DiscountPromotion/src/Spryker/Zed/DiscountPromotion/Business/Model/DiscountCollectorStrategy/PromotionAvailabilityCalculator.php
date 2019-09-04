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
     * @param int $idProductAbstract
     * @param int $maxQuantity
     *
     * @return int
     */
    public function getMaximumQuantityBasedOnAvailability(int $idProductAbstract, int $maxQuantity): int
    {
        $productAbstractAvailabilityTransfer = $this->getProductAbstractAvailability($idProductAbstract);

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

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    protected function getProductAbstractAvailability($idProductAbstract)
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $productAbstractAvailabilityTransfer = $this->availabilityFacade
            ->getProductAbstractAvailability(
                $idProductAbstract,
                $localeTransfer->getIdLocale()
            );

        return $productAbstractAvailabilityTransfer;
    }
}
