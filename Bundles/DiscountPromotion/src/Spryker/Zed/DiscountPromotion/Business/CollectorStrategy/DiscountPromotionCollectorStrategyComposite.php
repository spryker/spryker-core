<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\CollectorStrategy;

use Generated\Shared\Transfer\DiscountPromotionConditionsTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface;

class DiscountPromotionCollectorStrategyComposite implements DiscountPromotionCollectorStrategyCompositeInterface
{
    /**
     * @var array<\Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\DiscountPromotionCollectorStrategyInterface>
     */
    protected $discountPromotionCollectorStrategies;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface
     */
    protected $discountPromotionRepository;

    /**
     * @param array<\Spryker\Zed\DiscountPromotion\Business\CollectorStrategy\DiscountPromotionCollectorStrategyInterface> $discountPromotionCollectorStrategies
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface $discountPromotionRepository
     */
    public function __construct(
        array $discountPromotionCollectorStrategies,
        DiscountPromotionRepositoryInterface $discountPromotionRepository
    ) {
        $this->discountPromotionCollectorStrategies = $discountPromotionCollectorStrategies;
        $this->discountPromotionRepository = $discountPromotionRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer): array
    {
        $quoteTransfer->requireStore();

        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addIdDiscount($discountTransfer->getIdDiscount());
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        $discountPromotionTransfer = $this->discountPromotionRepository
            ->findDiscountPromotionByCriteria($discountPromotionCriteriaTransfer);

        if (!$discountPromotionTransfer) {
            return [];
        }

        return $this->executeDiscountPromotionCollectorStrategies(
            $discountPromotionTransfer,
            $discountTransfer,
            $quoteTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    protected function executeDiscountPromotionCollectorStrategies(
        DiscountPromotionTransfer $discountPromotionTransfer,
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer
    ): array {
        foreach ($this->discountPromotionCollectorStrategies as $discountPromotionCollectorStrategy) {
            if (
                !$discountPromotionCollectorStrategy->isApplicable(
                    $discountPromotionTransfer,
                    $discountTransfer,
                    $quoteTransfer,
                )
            ) {
                continue;
            }

            return $discountPromotionCollectorStrategy->collect(
                $discountPromotionTransfer,
                $discountTransfer,
                $quoteTransfer,
            );
        }

        return [];
    }
}
