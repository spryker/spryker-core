<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Dependency\Facade;

interface ProductReviewSearchToProductPageSearchFacadeInterface
{
    /**
     * @param array<int, int> $productAbstractIdTimestampMap
     *
     * @return void
     */
    public function publishWithTimestamp(array $productAbstractIdTimestampMap): void;
}
