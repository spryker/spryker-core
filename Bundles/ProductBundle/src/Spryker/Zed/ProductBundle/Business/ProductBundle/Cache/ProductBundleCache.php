<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cache;

use Spryker\Zed\ProductBundle\Business\ProductBundle\Exception\CacheValueNotFoundException;

class ProductBundleCache implements ProductBundleCacheInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CACHE_NOT_FOUND = 'Cache value for ProductForBundleTransfer by provided sku wasn\'t found';

    /**
     * @var array<array<\Generated\Shared\Transfer\ProductForBundleTransfer>>
     */
    protected static $groupedBySkuProductForBundleTransfers = [];

    /**
     * @param array<\Generated\Shared\Transfer\ProductForBundleTransfer> $productForBundleTransfers
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
     * @param array<\Generated\Shared\Transfer\ProductForBundleTransfer> $productForBundleTransfers
     * @param array<string> $skus
     *
     * @return void
     */
    public function cacheProductForBundleTransfersBySkus(array $productForBundleTransfers, array $skus): void
    {
        $this->cacheProductForBundleTransfersBySku($productForBundleTransfers);

        foreach ($skus as $sku) {
            $this->cacheEmptyProductForBundleTransfersBySku($sku);
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
     * @return array<\Generated\Shared\Transfer\ProductForBundleTransfer>
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

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function cacheEmptyProductForBundleTransfersBySku(string $sku): void
    {
        if (!isset(static::$groupedBySkuProductForBundleTransfers[$sku])) {
            static::$groupedBySkuProductForBundleTransfers[$sku] = null;
        }
    }
}
