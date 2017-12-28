<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetPageSearch\Dependency\Client;

use Spryker\Client\ProductSetStorage\ProductSetStorageClientInterface;

class ProductSetPageSearchToProductSetStorageClientBridge implements ProductSetPageSearchToProductSetStorageClientInterface
{
    /**
     * @var ProductSetStorageClientInterface
     */
    protected $productSetPageStorageClient;

    /**
     * @param ProductSetStorageClientInterface $productSetPageStorageClient
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
