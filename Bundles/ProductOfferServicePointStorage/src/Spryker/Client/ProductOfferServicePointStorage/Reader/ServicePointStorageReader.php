<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Reader;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageConditionsTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToServicePointStorageClientInterface;
use Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToStoreClientInterface;

class ServicePointStorageReader implements ServicePointStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToServicePointStorageClientInterface
     */
    protected ProductOfferServicePointStorageToServicePointStorageClientInterface $servicePointStorageClient;

    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToStoreClientInterface
     */
    protected ProductOfferServicePointStorageToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToServicePointStorageClientInterface $servicePointStorageClient
     * @param \Spryker\Client\ProductOfferServicePointStorage\Dependency\Client\ProductOfferServicePointStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        ProductOfferServicePointStorageToServicePointStorageClientInterface $servicePointStorageClient,
        ProductOfferServicePointStorageToStoreClientInterface $storeClient
    ) {
        $this->servicePointStorageClient = $servicePointStorageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageCollectionByServicePointUuids(
        array $servicePointUuids
    ): ServicePointStorageCollectionTransfer {
        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())
            ->setServicePointStorageConditions(
                (new ServicePointStorageConditionsTransfer())
                    ->setUuids($servicePointUuids)
                    ->setStoreName($this->storeClient->getCurrentStore()->getNameOrFail()),
            );

        return $this->servicePointStorageClient->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);
    }
}
