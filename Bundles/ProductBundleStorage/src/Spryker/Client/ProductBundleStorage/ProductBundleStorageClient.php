<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductBundleStorage\ProductBundleStorageFactory getFactory()
 */
class ProductBundleStorageClient extends AbstractClient implements ProductBundleStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @phpstan-return array<int, \Generated\Shared\Transfer\ProductBundleStorageTransfer>
     *
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer[]
     */
    public function getProductBundles(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->createProductBundleStorageReader()
            ->getProductBundles($productConcreteIds);
    }
}
