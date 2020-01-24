<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model\Mapper;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;

interface DiscountPromotionMapperInterface
{
    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function mapTransfer(SpyDiscountPromotion $discountPromotionEntity);

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function mapDiscountPromotionEntityToTransfer(
        SpyDiscountPromotion $discountPromotionEntity,
        DiscountPromotionTransfer $discountPromotionTransfer
    ): DiscountPromotionTransfer;

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion
     */
    public function mapEntity(
        SpyDiscountPromotion $discountPromotionEntity,
        DiscountPromotionTransfer $discountPromotionTransfer
    );
}
