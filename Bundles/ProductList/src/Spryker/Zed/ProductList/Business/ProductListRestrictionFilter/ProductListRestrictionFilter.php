<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\ProductListRestrictionFilter;

use Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface;

class ProductListRestrictionFilter implements ProductListRestrictionFilterInterface
{
    /**
     * @var \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface
     */
    protected $productListReader;

    /**
     * @param \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface $productListReader
     */
    public function __construct(
        ProductListReaderInterface $productListReader
    ) {
        $this->productListReader = $productListReader;
    }

    /**
     * @param array<string> $productConcreteSkus
     * @param array<int> $customerBlacklistIds
     * @param array<int> $customerWhitelistIds
     *
     * @return array<string>
     */
    public function filterRestrictedProductConcreteSkus(array $productConcreteSkus, array $customerBlacklistIds, array $customerWhitelistIds): array
    {
        if (!$productConcreteSkus) {
            return [];
        }

        $restrictedProductConcreteSkus = [];

        if ($customerWhitelistIds) {
            $productConcreteSkusInWhitelist = $this->productListReader
                ->getProductConcreteSkusInWhitelists($productConcreteSkus, $customerWhitelistIds);

            $restrictedProductConcreteSkus = array_diff($productConcreteSkus, $productConcreteSkusInWhitelist);
        }

        if ($customerBlacklistIds) {
            $productConcreteSkusInBlacklist = $this->productListReader
                ->getProductConcreteSkusInBlacklists($productConcreteSkus, $customerBlacklistIds);

            $restrictedProductConcreteSkus = array_merge($productConcreteSkusInBlacklist, $restrictedProductConcreteSkus);
        }

        return array_unique($restrictedProductConcreteSkus);
    }
}
