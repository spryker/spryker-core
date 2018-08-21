<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;

class ProductImageSetsRestApiToProductImageResourceAliasStorageClientBridge implements ProductImageSetsRestApiToProductImageResourceAliasStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductImageResourceAliasStorage\ProductImageResourceAliasStorageClientInterface
     */
    protected $productImageResourceAliasStorageClient;

    /**
     * @param \Spryker\Client\ProductImageResourceAliasStorage\ProductImageResourceAliasStorageClientInterface $productImageResourceAliasStorageClient
     */
    public function __construct($productImageResourceAliasStorageClient)
    {
        $this->productImageResourceAliasStorageClient = $productImageResourceAliasStorageClient;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer|null
     */
    public function findProductImageConcreteStorageTransfer(string $sku, string $localeName): ?ProductConcreteImageStorageTransfer
    {
        return $this->productImageResourceAliasStorageClient->findProductImageConcreteStorageTransfer($sku, $localeName);
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer|null
     */
    public function findProductImageAbstractStorageTransfer(string $sku, string $localeName): ?ProductAbstractImageStorageTransfer
    {
        return $this->productImageResourceAliasStorageClient->findProductImageAbstractStorageTransfer($sku, $localeName);
    }
}
