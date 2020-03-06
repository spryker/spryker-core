<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Cache;

use Generated\Shared\Transfer\ProductBundleTransfer;

interface ProductBundleCacheInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     */
    public function cacheProductForBundleTransfersBySku(array $productForBundleTransfers): void;

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductForBundleTransfersBySku(string $sku): bool;

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductBundleTransfer[]
     */
    public function getProductForBundleTransfersBySku(string $sku): array;
}
