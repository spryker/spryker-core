<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Dependency\Client;

interface ProductBundlesRestApiToProductBundleStorageClientInterface
{
    /**
     * @phpstan-return array<int, \Generated\Shared\Transfer\ProductBundleStorageTransfer>
     *
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer[]
     */
    public function getProductBundles(array $productConcreteIds): array;
}
