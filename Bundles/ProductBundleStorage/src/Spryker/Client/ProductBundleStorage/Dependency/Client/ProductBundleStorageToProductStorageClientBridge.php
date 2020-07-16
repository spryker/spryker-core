<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage\Dependency\Client;

class ProductBundleStorageToProductStorageClientBridge implements ProductBundleStorageToProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     */
    public function __construct($productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param int[] $productConcreteIds
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductConcreteViewTransfers(array $productConcreteIds, string $localeName, array $selectedAttributes = []): array
    {
        return $this->productStorageClient->getProductConcreteViewTransfers($productConcreteIds, $localeName, $selectedAttributes);
    }
}
