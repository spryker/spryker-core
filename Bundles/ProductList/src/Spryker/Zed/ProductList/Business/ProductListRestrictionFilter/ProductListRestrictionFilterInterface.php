<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductListRestrictionFilter;

interface ProductListRestrictionFilterInterface
{
    /**
     * @param string[] $productConcreteSkus
     * @param int[] $customerBlacklistIds
     * @param int[] $customerWhitelistIds
     *
     * @return string[]
     */
    public function filterRestrictedProductConcreteSkus(array $productConcreteSkus, array $customerBlacklistIds, array $customerWhitelistIds): array;
}
