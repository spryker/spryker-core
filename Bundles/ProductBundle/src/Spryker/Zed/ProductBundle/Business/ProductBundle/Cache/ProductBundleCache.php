<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cache;

use Spryker\Zed\ProductBundle\Business\ProductBundle\Exception\CacheValueNotFoundException;

class ProductBundleCache implements ProductBundleCacheInterface
{
    protected const ERROR_MESSAGE_CACHE_NOT_FOUND = 'Cache value for ProductForBundleTransfer by provided sku wasn\'t found';

    /**
     * @var \Generated\Shared\Transfer\ProductForBundleTransfer[][]
     */
    protected static $groupedBySkuProductForBundleTransfers = [];

    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     *
     * @return void
     */
    public function cacheProductForBundleTransfersBySku(array $productForBundleTransfers): void
    {
        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            static::$groupedBySkuProductForBundleTransfers[$productForBundleTransfer->getBundleSku()][] = $productForBundleTransfer;
        }
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductForBundleTransfersBySku(string $sku): bool
    {
        return isset(static::$groupedBySkuProductForBundleTransfers[$sku]);
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\ProductBundle\Business\ProductBundle\Exception\CacheValueNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function getProductForBundleTransfersBySku(string $sku): array
    {
        if (!$this->hasProductForBundleTransfersBySku($sku)) {
            throw new CacheValueNotFoundException(static::ERROR_MESSAGE_CACHE_NOT_FOUND);
        }

        return static::$groupedBySkuProductForBundleTransfers[$sku];
    }

    /**
     * @return void
     */
    public function cleanCache(): void
    {
        static::$groupedBySkuProductForBundleTransfers = [];
    }
}
