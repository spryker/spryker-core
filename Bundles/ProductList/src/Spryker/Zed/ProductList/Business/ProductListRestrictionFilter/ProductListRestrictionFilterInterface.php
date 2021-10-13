<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductListRestrictionFilter;

interface ProductListRestrictionFilterInterface
{
    /**
     * @param array<string> $productConcreteSkus
     * @param array<int> $customerBlacklistIds
     * @param array<int> $customerWhitelistIds
     *
     * @return array<string>
     */
    public function filterRestrictedProductConcreteSkus(array $productConcreteSkus, array $customerBlacklistIds, array $customerWhitelistIds): array;
}
