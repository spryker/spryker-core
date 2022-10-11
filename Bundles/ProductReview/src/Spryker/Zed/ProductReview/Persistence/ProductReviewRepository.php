<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Persistence;

use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductReview\Persistence\ProductReviewPersistenceFactory getFactory()
 */
class ProductReviewRepository extends AbstractRepository implements ProductReviewRepositoryInterface
{
    /**
     * @var string
     */
    protected const COL_AVERAGE_RATING = 'avg_rating';

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, float>
     */
    public function getProductRatingsByProductAbstractIds(array $productAbstractIds): array
    {
        $ratingsByProductAbstractIds = [];
        $productReviews = $this->getFactory()
            ->createProductReviewQuery()
            ->select(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT)
            ->withColumn('AVG(' . SpyProductReviewTableMap::COL_RATING . ')', static::COL_AVERAGE_RATING)
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->filterByStatus(SpyProductReviewTableMap::COL_STATUS_APPROVED)
            ->groupByFkProductAbstract()
            ->find()
            ->getData();

        foreach ($productReviews as $productReview) {
            $idProductAbstract = (int)$productReview[SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT];
            $rating = (float)$productReview[static::COL_AVERAGE_RATING];

            $ratingsByProductAbstractIds[$idProductAbstract] = $rating;
        }

        return $ratingsByProductAbstractIds;
    }
}
