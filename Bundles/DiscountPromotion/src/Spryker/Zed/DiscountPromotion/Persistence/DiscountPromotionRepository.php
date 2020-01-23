<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionPersistenceFactory getFactory()
 */
class DiscountPromotionRepository extends AbstractRepository implements DiscountPromotionRepositoryInterface
{
    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByUuid(string $uuid): ?DiscountPromotionTransfer
    {
        $discountPromotionEntity = $this->getFactory()
            ->createDiscountPromotionQuery()
            ->findOneByUuid($uuid);

        if ($discountPromotionEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createDiscountPromotionMapper()
            ->mapDiscountPromotionEntityToTransfer($discountPromotionEntity, new DiscountPromotionTransfer());
    }
}
