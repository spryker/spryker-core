<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage;

interface ProductBundleStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves bundle products from storage by provided product concrete ids.
     * - Returns `ProductBundleStorageTransfer` collection indexed by product concrete id.
     *
     * @api
     *
     * @phpstan-return array<int, \Generated\Shared\Transfer\ProductBundleStorageTransfer>
     *
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer[]
     */
    public function getProductBundles(array $productConcreteIds): array;
}
