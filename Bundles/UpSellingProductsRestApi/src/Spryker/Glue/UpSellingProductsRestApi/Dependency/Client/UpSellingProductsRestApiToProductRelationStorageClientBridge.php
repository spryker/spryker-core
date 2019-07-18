<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UpSellingProductsRestApi\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

class UpSellingProductsRestApiToProductRelationStorageClientBridge implements UpSellingProductsRestApiToProductRelationStorageClientInterface
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    public function findUpSellingAbstractProductIds(QuoteTransfer $quoteTransfer): array
    {
        return $this->productRelationStorageClient->findUpSellingAbstractProductIds($quoteTransfer);
    }
}
