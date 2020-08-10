<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Calculator;

use Generated\Shared\Transfer\ProductReviewSummaryTransfer;
use Generated\Shared\Transfer\RatingAggregationTransfer;

interface ProductReviewSummaryCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RatingAggregationTransfer $ratingAggregationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewSummaryTransfer
     */
    public function calculate(RatingAggregationTransfer $ratingAggregationTransfer): ProductReviewSummaryTransfer;
}
