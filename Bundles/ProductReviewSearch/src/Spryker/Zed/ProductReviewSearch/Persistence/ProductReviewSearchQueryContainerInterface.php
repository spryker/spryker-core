<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductReviewSearchQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productReviewIds
     *
     * @return \Orm\Zed\ProductReviewSearch\Persistence\SpyProductReviewSearchQuery
     */
    public function queryProductReviewSearchByIds(array $productReviewIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productReviewIds
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReviewsByIdProductReviews(array $productReviewIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idAbstractProduct
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|\Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReviewRatingByIdAbstractProduct($idAbstractProduct);
}
