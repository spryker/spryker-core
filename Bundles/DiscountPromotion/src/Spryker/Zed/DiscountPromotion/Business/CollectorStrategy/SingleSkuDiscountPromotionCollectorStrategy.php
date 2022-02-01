<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\CollectorStrategy;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountableItemCreatorInterface;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface;

class SingleSkuDiscountPromotionCollectorStrategy implements DiscountPromotionCollectorStrategyInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountableItemCreatorInterface
     */
    protected $discountableItemCreator;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface
     */
    protected $discountPromotionRepository;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionCreator\DiscountableItemCreatorInterface $discountableItemCreator
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface $discountPromotionRepository
     */
    public function __construct(
        DiscountableItemCreatorInterface $discountableItemCreator,
        DiscountPromotionRepositoryInterface $discountPromotionRepository
    ) {
        $this->discountableItemCreator = $discountableItemCreator;
        $this->discountPromotionRepository = $discountPromotionRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(
        DiscountPromotionTransfer $discountPromotionTransfer,
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer
    ): bool {
        return !$this->discountPromotionRepository->isAbstractSkusFieldExists();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(
        DiscountPromotionTransfer $discountPromotionTransfer,
        DiscountTransfer $discountTransfer,
        QuoteTransfer $quoteTransfer
    ): array {
        $discountableItemTransfer = $this->discountableItemCreator->createDiscountableItemBySku(
            $discountPromotionTransfer->getAbstractSku(),
            $quoteTransfer,
            $discountPromotionTransfer,
            $discountTransfer,
        );

        if (!$discountableItemTransfer) {
            return [];
        }

        return [$discountableItemTransfer];
    }
}
