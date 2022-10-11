<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Persistence;

interface ProductReviewRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, float>
     */
    public function getProductRatingsByProductAbstractIds(array $productAbstractIds): array;
}
