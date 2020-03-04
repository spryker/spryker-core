<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Search;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;

interface ProductReviewSearchReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer
     *
     * @return array
     */
    public function findProductReviews(ProductReviewSearchRequestTransfer $productReviewSearchRequestTransfer): array;
}
