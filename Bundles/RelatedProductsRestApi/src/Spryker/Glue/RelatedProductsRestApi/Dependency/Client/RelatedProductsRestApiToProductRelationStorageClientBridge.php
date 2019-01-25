<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RelatedProductsRestApi\Dependency\Client;

class RelatedProductsRestApiToProductRelationStorageClientBridge implements RelatedProductsRestApiToProductRelationStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductRelationStorage\ProductRelationStorageClientInterface
     */
    protected $productRelationStorageClient;

    /**
     * @param \Spryker\Client\ProductRelationStorage\ProductRelationStorageClientInterface $productRelationStorageClient
     */
    public function __construct($productRelationStorageClient)
    {
        $this->productRelationStorageClient = $productRelationStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findRelatedAbstractProductIds(int $idProductAbstract): array
    {
        return $this->productRelationStorageClient->findRelatedAbstractProductIds($idProductAbstract);
    }
}
