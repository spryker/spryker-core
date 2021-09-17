<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cache;

interface ProductBundleCacheInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductForBundleTransfer> $productForBundleTransfers
     *
     * @return void
     */
    public function cacheProductForBundleTransfersBySku(array $productForBundleTransfers): void;

    /**
     * @param array<\Generated\Shared\Transfer\ProductForBundleTransfer> $productForBundleTransfers
     * @param array<string> $skus
     *
     * @return void
     */
    public function cacheProductForBundleTransfersBySkus(array $productForBundleTransfers, array $skus): void;

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductForBundleTransfersBySku(string $sku): bool;

    /**
     * @param string $sku
     *
     * @return array<\Generated\Shared\Transfer\ProductForBundleTransfer>
     */
    public function getProductForBundleTransfersBySku(string $sku): array;

    /**
     * @return void
     */
    public function cleanCache(): void;
}
