<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AlternativeProductsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;

class AlternativeProductsRestApiToProductAlternativeStorageClientBridge implements AlternativeProductsRestApiToProductAlternativeStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductAlternativeStorage\ProductAlternativeStorageClientInterface
     */
    protected $productAlternativeStorageClient;

    /**
     * @param \Spryker\Client\ProductAlternativeStorage\ProductAlternativeStorageClientInterface $productAlternativeStorageClient
     */
    public function __construct($productAlternativeStorageClient)
    {
        $this->productAlternativeStorageClient = $productAlternativeStorageClient;
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeStorageTransfer|null
     */
    public function findProductAlternativeStorage(string $concreteSku): ?ProductAlternativeStorageTransfer
    {
        return $this->productAlternativeStorageClient->findProductAlternativeStorage($concreteSku);
    }
}
