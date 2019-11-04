<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductRestrictionFilter;

interface ProductRestrictionFilterInterface
{
    /**
     * @param int[] $productIds
     *
     * @return int[]
     */
    public function filterRestrictedProducts(array $productIds): array;
}
