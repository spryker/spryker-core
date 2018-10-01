<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Persistence;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchPersistenceFactory getFactory()
 */
class ProductReviewSearchQueryContainer extends AbstractQueryContainer implements ProductReviewSearchQueryContainerInterface
{
    public const FIELD_FK_PRODUCT_ABSTRACT = ProductPageSearchTransfer::ID_PRODUCT_ABSTRACT;
    public const FIELD_AVERAGE_RATING = ProductPageSearchTransfer::AVERAGE_RATING;
    public const FIELD_COUNT = ProductPageSearchTransfer::REVIEW_COUNT;

    /**
     * @api
     *
     * @param array $productReviewIds
     *
     * @return \Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearchQuery
     */
    public function queryProductReviewSearchByIds(array $productReviewIds)
    {
        return $this
            ->getFactory()
            ->createSpyProductReviewSearchQuery()
            ->filterByFkProductReview_In($productReviewIds);
    }

    /**
     * @api
     *
     * @param array $productReviewIds
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReviewsByIdProductReviews(array $productReviewIds)
    {
        return $this->getFactory()
            ->getProductReviewQuery()
            ->queryProductReview()
            ->filterByIdProductReview_In($productReviewIds);
    }

    /**
     * @api
     *
     * @param int $idAbstractProduct
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|\Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReviewRatingByIdAbstractProduct($idAbstractProduct)
    {
        return $this->getFactory()
            ->getProductReviewQuery()
            ->queryProductReview()
            ->filterByFkProductAbstract($idAbstractProduct)
            ->filterByStatus(SpyProductReviewTableMap::COL_STATUS_APPROVED)
            ->withColumn(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_FK_PRODUCT_ABSTRACT)
            ->withColumn(sprintf('AVG(%s)', SpyProductReviewTableMap::COL_RATING), static::FIELD_AVERAGE_RATING)
            ->withColumn(sprintf('COUNT(%s)', SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT), static::FIELD_COUNT)
            ->select([static::FIELD_FK_PRODUCT_ABSTRACT, static::FIELD_AVERAGE_RATING, static::FIELD_COUNT])
            ->groupBy(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT);
    }
}
