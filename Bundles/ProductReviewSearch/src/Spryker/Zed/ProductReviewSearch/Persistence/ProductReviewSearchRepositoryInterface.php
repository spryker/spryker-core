<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Persistence;

interface ProductReviewSearchRepositoryInterface
{
    /**
     * @param array $abstractProductIds
     *
     * @return array
     */
    public function getProductReviewRatingByIdAbstractProductIn(array $abstractProductIds): array;
}
