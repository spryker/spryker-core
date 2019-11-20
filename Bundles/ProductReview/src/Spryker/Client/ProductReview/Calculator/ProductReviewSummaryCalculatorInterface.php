<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Calculator;

interface ProductReviewSummaryCalculatorInterface
{
    /**
     * @param array $ratingAggregation
     *
     * @return \Generated\Shared\Transfer\ProductReviewSummaryTransfer
     */
    public function execute(array $ratingAggregation);
}
