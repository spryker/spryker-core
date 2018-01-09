<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetPageSearch\Dependency\Client;

class ProductSetPageSearchToProductSetStorageClientBridge implements ProductSetPageSearchToProductSetStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductSetStorage\ProductSetStorageClientInterface
     */
    protected $productSetPageStorageClient;

    /**
     * @param \Spryker\Client\ProductSetStorage\ProductSetStorageClientInterface $productSetPageStorageClient
     */
    public function __construct($productSetPageStorageClient)
    {
        $this->productSetPageStorageClient = $productSetPageStorageClient;
    }

    /**
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function mapProductSetStorageDataToTransfer(array $productSetStorageData)
    {
        return $this->productSetPageStorageClient->mapProductSetStorageDataToTransfer($productSetStorageData);
    }
}
