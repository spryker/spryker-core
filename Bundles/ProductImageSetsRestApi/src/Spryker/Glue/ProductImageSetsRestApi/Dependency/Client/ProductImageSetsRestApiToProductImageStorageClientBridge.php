<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;

class ProductImageSetsRestApiToProductImageStorageClientBridge implements ProductImageSetsRestApiToProductImageStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductImageStorage\ProductImageStorageClientInterface
     */
    protected $productImageStorageClient;

    /**
     * @param \Spryker\Client\ProductImageStorage\ProductImageStorageClientInterface $productImageStorageClient
     */
    public function __construct($productImageStorageClient)
    {
        $this->productImageStorageClient = $productImageStorageClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer|null
     */
    public function findProductImageAbstractStorageTransfer(int $idProductAbstract, string $locale): ?ProductAbstractImageStorageTransfer
    {
        return $this->productImageStorageClient->findProductImageAbstractStorageTransfer($idProductAbstract, $locale);
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetStorageTransfer[]|null
     */
    public function resolveProductImageSetStorageTransfers(
        int $idProductConcrete,
        int $idProductAbstract,
        string $locale
    ): ?array {
        return $this->productImageStorageClient->resolveProductImageSetStorageTransfers(
            $idProductConcrete,
            $idProductAbstract,
            $locale
        );
    }
}
