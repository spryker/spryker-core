<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Reader;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface;

class DiscountPromotionReader implements DiscountPromotionReaderInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface
     */
    protected $discountPromotionRepository;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface $discountPromotionRepository
     */
    public function __construct(DiscountPromotionRepositoryInterface $discountPromotionRepository)
    {
        $this->discountPromotionRepository = $discountPromotionRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByUuid(DiscountPromotionTransfer $discountPromotionTransfer): ?DiscountPromotionTransfer
    {
        $discountPromotionTransfer->requireUuid();

        return $this->discountPromotionRepository->findDiscountPromotionByUuid($discountPromotionTransfer->getUuid());
    }
}
