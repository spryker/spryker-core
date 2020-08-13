<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage;

interface ProductBundleStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves bundle products from storage by provided product concrete ids.
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer[]
     */
    public function getProductBundles(array $productConcreteIds): array;
}
