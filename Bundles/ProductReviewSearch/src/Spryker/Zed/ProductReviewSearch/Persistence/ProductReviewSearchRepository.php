<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Persistence;

use Generated\Shared\Transfer\ProductPayloadTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchPersistenceFactory getFactory()
 */
class ProductReviewSearchRepository extends AbstractRepository implements ProductReviewSearchRepositoryInterface
{
    protected const FIELD_FK_PRODUCT_ABSTRACT = ProductPayloadTransfer::ID_PRODUCT_ABSTRACT;
    protected const FIELD_AVERAGE_RATING = ProductPayloadTransfer::AVERAGE_RATING;
    protected const FIELD_REVIEW_COUNT = ProductPayloadTransfer::REVIEW_COUNT;

    /**
     * @param array $abstractProductIds
     *
     * @return array
     */
    public function getProductReviewRatingByIdAbstractProductIn(array $abstractProductIds): array
    {
        return $this->getFactory()
            ->getPropelProductReviewQuery()
            ->filterByFkProductAbstract_In($abstractProductIds)
            ->filterByStatus(SpyProductReviewTableMap::COL_STATUS_APPROVED)
            ->withColumn(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_FK_PRODUCT_ABSTRACT)
            ->withColumn(sprintf('AVG(%s)', SpyProductReviewTableMap::COL_RATING), static::FIELD_AVERAGE_RATING)
            ->withColumn(sprintf('COUNT(%s)', SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT), static::FIELD_REVIEW_COUNT)
            ->select([static::FIELD_FK_PRODUCT_ABSTRACT, static::FIELD_AVERAGE_RATING, static::FIELD_REVIEW_COUNT])
            ->groupBy(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->find()
            ->toArray(static::FIELD_FK_PRODUCT_ABSTRACT);
    }
}
