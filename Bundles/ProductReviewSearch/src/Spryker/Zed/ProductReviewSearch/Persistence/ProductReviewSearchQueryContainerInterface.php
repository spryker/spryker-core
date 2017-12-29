<?php

namespace Spryker\Zed\ProductReviewSearch\Persistence;

use Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery;
use Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearchQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductReviewSearchQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param array $productReviewIds
     *
     * @return SpyProductReviewSearchQuery
     */
    public function queryProductReviewSearchByIds(array $productReviewIds);

    /**
     * @param array $productReviewIds
     *
     * @return SpyProductReviewQuery
     */
    public function queryProductReviewsByIdProductReviews(array $productReviewIds);

    /**
     * @param int $idAbstractProduct
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria|SpyProductReviewQuery
     */
    public function queryProductReviewRatingByIdAbstractProduct($idAbstractProduct);
}
