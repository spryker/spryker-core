<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client;

class ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientBridge implements ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageClientInterface
     */
    protected $productOfferAvailabilityStorageClient;

    /**
     * @param \Spryker\Client\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageClientInterface $productOfferAvailabilityStorageClient
     */
    public function __construct($productOfferAvailabilityStorageClient)
    {
        $this->productOfferAvailabilityStorageClient = $productOfferAvailabilityStorageClient;
    }

    /**
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer[]
     */
    public function getByProductOfferReferences(array $productOfferReferences, string $storeName): array
    {
        return $this->productOfferAvailabilityStorageClient->getByProductOfferReferences($productOfferReferences, $storeName);
    }
}
