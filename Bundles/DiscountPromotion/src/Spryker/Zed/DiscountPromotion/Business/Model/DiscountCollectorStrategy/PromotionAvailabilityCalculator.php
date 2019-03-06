<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model\DiscountCollectorStrategy;

use Spryker\Service\DiscountPromotion\DiscountPromotionServiceInterface;
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
     * @var \Spryker\Service\DiscountPromotion\DiscountPromotionServiceInterface
     */
    protected $service;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToAvailabilityInterface $availabilityFacade
     * @param \Spryker\Zed\DiscountPromotion\Dependency\Facade\DiscountPromotionToLocaleInterface $localeFacade
     * @param \Spryker\Service\DiscountPromotion\DiscountPromotionServiceInterface $service
     */
    public function __construct(
        DiscountPromotionToAvailabilityInterface $availabilityFacade,
        DiscountPromotionToLocaleInterface $localeFacade,
        DiscountPromotionServiceInterface $service
    ) {

        $this->availabilityFacade = $availabilityFacade;
        $this->localeFacade = $localeFacade;
        $this->service = $service;
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

        $availability = $this->service->round($productAbstractAvailabilityTransfer->getAvailability());

        if ($availability <= 0.0) {
            return 0.0;
        }

        if ($maxQuantity > $availability) {
            return $productAbstractAvailabilityTransfer->getAvailability();
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
