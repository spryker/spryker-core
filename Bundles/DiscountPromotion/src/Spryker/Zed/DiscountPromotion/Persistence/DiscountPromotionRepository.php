<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Generated\Shared\Transfer\DiscountPromotionCollectionTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionPersistenceFactory getFactory()
 */
class DiscountPromotionRepository extends AbstractRepository implements DiscountPromotionRepositoryInterface
{
    /**
     * @deprecated Will be removed in next major release.
     *
     * @return bool
     */
    public function isAbstractSkusFieldExists(): bool
    {
        return $this->getFactory()
            ->createDiscountPromotionFieldChecker()
            ->isAbstractSkusFieldExists();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionCollectionTransfer
     */
    public function getDiscountPromotionCollection(DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer): DiscountPromotionCollectionTransfer
    {
        $discountPromotionQuery = $this->getFactory()->createDiscountPromotionQuery();

        $discountPromotionQuery = $this->applyFilters($discountPromotionQuery, $discountPromotionCriteriaTransfer);

        return $this->getFactory()
            ->createDiscountPromotionMapper()
            ->mapDiscountPromotionEntitiesToDiscountPromotionCollectionTransfer(
                $discountPromotionQuery->find(),
                new DiscountPromotionCollectionTransfer(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
     *
     * @return bool
     */
    public function hasDiscountPromotion(DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer): bool
    {
        $discountPromotionQuery = $this->getFactory()->createDiscountPromotionQuery();

        $discountPromotionQuery = $this->applyFilters($discountPromotionQuery, $discountPromotionCriteriaTransfer);

        return $discountPromotionQuery->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByCriteria(
        DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
    ): ?DiscountPromotionTransfer {
        return $this->getDiscountPromotionCollection($discountPromotionCriteriaTransfer)
            ->getDiscountPromotions()
            ->getIterator()
            ->current();
    }

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery $discountPromotionQuery
     * @param \Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery
     */
    protected function applyFilters(
        SpyDiscountPromotionQuery $discountPromotionQuery,
        DiscountPromotionCriteriaTransfer $discountPromotionCriteriaTransfer
    ): SpyDiscountPromotionQuery {
        $discountPromotionConditionsTransfer = $discountPromotionCriteriaTransfer->getDiscountPromotionConditions();
        if (!$discountPromotionConditionsTransfer) {
            return $discountPromotionQuery;
        }

        if (
            $discountPromotionConditionsTransfer->getUuids()
            && $this->getFactory()->createDiscountPromotionFieldChecker()->isUuidFieldExists()
        ) {
            $discountPromotionQuery->filterByUuid_In($discountPromotionConditionsTransfer->getUuids());
        }

        if ($discountPromotionConditionsTransfer->getDiscountIds()) {
            $discountPromotionQuery->filterByFkDiscount_In($discountPromotionConditionsTransfer->getDiscountIds());
        }

        if ($discountPromotionConditionsTransfer->getDiscountPromotionIds()) {
            $discountPromotionQuery->filterByIdDiscountPromotion_In($discountPromotionConditionsTransfer->getDiscountPromotionIds());
        }

        return $discountPromotionQuery;
    }
}
