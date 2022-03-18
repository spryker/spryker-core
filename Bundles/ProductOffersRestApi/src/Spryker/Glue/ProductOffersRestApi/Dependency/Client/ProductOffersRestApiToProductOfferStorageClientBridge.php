<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOffersRestApi\Dependency\Client;

class ProductOffersRestApiToProductOfferStorageClientBridge implements ProductOffersRestApiToProductOfferStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface
     */
    protected $productOfferStorageClient;

    /**
     * @param \Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface $productOfferStorageClient
     */
    public function __construct($productOfferStorageClient)
    {
        $this->productOfferStorageClient = $productOfferStorageClient;
    }

    /**
     * @param array<string> $productOfferReferences
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function getProductOfferStoragesByReferences(array $productOfferReferences): array
    {
        return $this->productOfferStorageClient->getProductOfferStoragesByReferences($productOfferReferences);
    }
}
