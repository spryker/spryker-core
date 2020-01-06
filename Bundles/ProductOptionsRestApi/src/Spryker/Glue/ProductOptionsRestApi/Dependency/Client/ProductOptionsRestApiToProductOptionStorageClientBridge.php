<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Dependency\Client;

class ProductOptionsRestApiToProductOptionStorageClientBridge implements ProductOptionsRestApiToProductOptionStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductOptionStorage\ProductOptionStorageClientInterface
     */
    protected $productOptionStorageClient;

    /**
     * @param \Spryker\Client\ProductOptionStorage\ProductOptionStorageClientInterface $productOptionStorageClient
     */
    public function __construct($productOptionStorageClient)
    {
        $this->productOptionStorageClient = $productOptionStorageClient;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[]
     */
    public function getBulkProductOptions(array $productAbstractIds): array
    {
        return $this->productOptionStorageClient->getBulkProductOptions($productAbstractIds);
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    public function getProductOptionsForCurrentStore($idAbstractProduct)
    {
        return $this->productOptionStorageClient->getProductOptionsForCurrentStore($idAbstractProduct);
    }
}
