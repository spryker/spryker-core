<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Dependency\Client;

class ProductBundlesRestApiToProductBundleStorageClientBridge implements ProductBundlesRestApiToProductBundleStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface
     */
    protected $productBundleStorageClient;

    /**
     * @param \Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface $productBundleStorageClient
     */
    public function __construct($productBundleStorageClient)
    {
        $this->productBundleStorageClient = $productBundleStorageClient;
    }

    /**
     * @phpstan-return array<int, \Generated\Shared\Transfer\ProductBundleStorageTransfer>
     *
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer[]
     */
    public function getProductBundles(array $productConcreteIds): array
    {
        return $this->productBundleStorageClient->getProductBundles($productConcreteIds);
    }
}
