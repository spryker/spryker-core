<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Aggregation;

use Elastica\Aggregation\Terms;

interface AggregationBuilderInterface
{
    /**
     * @param string $name
     *
     * @return \Elastica\Aggregation\Terms
     */
    public function createTermsAggregation(string $name): Terms;
}
