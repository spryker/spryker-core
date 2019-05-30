<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy;

use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface;
use Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleInterface;
use Spryker\Zed\DiscountPromotion\Dependency\Service\DiscountPromotionToUtilQuantityServiceInterface;

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
     * @var \Spryker\Zed\DiscountPromotion\Dependency\Service\DiscountPromotionToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleInterface $localeFacade
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Service\DiscountPromotionToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        DiscountPromotionToAvailabilityInterface $availabilityFacade,
        DiscountPromotionToLocaleInterface $localeFacade,
        DiscountPromotionToUtilQuantityServiceInterface $utilQuantityService
    ) {

        $this->availabilityFacade = $availabilityFacade;
        $this->localeFacade = $localeFacade;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param int $idProductAbstract
     * @param float $maxQuantity
     *
     * @return float
     */
    public function getMaximumQuantityBasedOnAvailability($idProductAbstract, $maxQuantity)
    {
        $productAbstractAvailabilityTransfer = $this->getProductAbstractAvailability($idProductAbstract);

        if ($productAbstractAvailabilityTransfer->getIsNeverOutOfStock()) {
            return $maxQuantity;
        }
        $availability = $productAbstractAvailabilityTransfer->getAvailability();

        if ($this->isQuantityLessOrEqual($availability, 0)) {
            return 0.0;
        }

        if ($maxQuantity > $availability) {
            return $productAbstractAvailabilityTransfer->getAvailability();
        }

        return $maxQuantity;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityLessOrEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityLessOrEqual($firstQuantity, $secondQuantity);
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
