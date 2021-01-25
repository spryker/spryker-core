<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductReview\Persistence\ProductReviewPersistenceFactory getFactory()
 */
class ProductReviewQueryContainer extends AbstractQueryContainer implements ProductReviewQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductReview
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReviewById($idProductReview)
    {
        return $this->getFactory()
            ->createProductReviewQuery()
            ->filterByIdProductReview($idProductReview);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery
     */
    public function queryProductReview()
    {
        return $this->getFactory()
            ->createProductReviewQuery();
    }
}
