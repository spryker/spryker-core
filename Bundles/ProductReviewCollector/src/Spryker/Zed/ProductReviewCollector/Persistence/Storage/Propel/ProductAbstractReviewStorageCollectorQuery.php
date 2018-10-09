<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector\Persistence\Storage\Propel;

use Generated\Shared\Transfer\ProductAbstractReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class ProductAbstractReviewStorageCollectorQuery extends AbstractPropelCollectorQuery
{
    public const FIELD_FK_PRODUCT_ABSTRACT = ProductAbstractReviewTransfer::ID_PRODUCT_ABSTRACT;
    public const FIELD_AVERAGE_RATING = ProductAbstractReviewTransfer::AVERAGE_RATING;
    public const FIELD_COUNT = ProductAbstractReviewTransfer::REVIEW_COUNT;

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery
            ->addJoin(
                [SpyTouchTableMap::COL_ITEM_ID, SpyProductReviewTableMap::COL_STATUS],
                [SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT, $this->getApprovedReviewStatus()],
                Criteria::INNER_JOIN
            )
            ->addGroupByColumn(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT);

        $this->touchQuery
            ->withColumn(SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_FK_PRODUCT_ABSTRACT)
            ->withColumn(sprintf('AVG(%s)', SpyProductReviewTableMap::COL_RATING), static::FIELD_AVERAGE_RATING)
            ->withColumn(sprintf('COUNT(%s)', SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT), static::FIELD_COUNT);
    }

    /**
     * @return int
     */
    protected function getApprovedReviewStatus()
    {
        $productReviewStatusValueSet = SpyProductReviewTableMap::getValueSet(SpyProductReviewTableMap::COL_STATUS);
        /** @var int $convertedStatus */
        $convertedStatus = array_search(SpyProductReviewTableMap::COL_STATUS_APPROVED, $productReviewStatusValueSet);

        return $convertedStatus;
    }
}
