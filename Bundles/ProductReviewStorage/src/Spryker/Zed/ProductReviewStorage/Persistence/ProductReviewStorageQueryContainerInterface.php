<?php

namespace Spryker\Zed\ProductReviewStorage\Persistence;

use Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery;
use Orm\Zed\ProductReviewStorage\Persistence\SpyProductAbstractReviewStorageQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductReviewStorageQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param array $productAbstractIds
     *
     * @return SpyProductAbstractReviewStorageQuery
     */
    public function queryProductAbstractReviewStorageByIds(array $productAbstractIds);

    /**
     * @param array $productAbstractIds
     *
     * @return SpyProductReviewQuery
     */
    public function queryProductReviewsByIdProductAbstracts(array $productAbstractIds);
}
