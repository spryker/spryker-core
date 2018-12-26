<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductDiscontinuedRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;

class ProductDiscontinuedRestApiToProductDiscontinuedStorageClientBridge implements ProductDiscontinuedRestApiToProductDiscontinuedStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface
     */
    protected $productDiscontinuedStorageClient;

    /**
     * @param \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface $productDiscontinuedStorageClient
     */
    public function __construct($productDiscontinuedStorageClient)
    {
        $this->productDiscontinuedStorageClient = $productDiscontinuedStorageClient;
    }

    /**
     * @param string $concreteSku
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer|null
     */
    public function findProductDiscontinuedStorage(string $concreteSku, string $locale): ?ProductDiscontinuedStorageTransfer
    {
        return $this->productDiscontinuedStorageClient->findProductDiscontinuedStorage($concreteSku, $locale);
    }
}
