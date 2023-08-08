<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageConditionsTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface;

class ServicePointStorageReader implements ServicePointStorageReaderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface
     */
    protected ServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface
     */
    protected ServicePointsRestApiToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient
     * @param \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToStoreClientInterface $storeClient
     */
    public function __construct(
        ServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient,
        ServicePointsRestApiToStoreClientInterface $storeClient
    ) {
        $this->servicePointStorageClient = $servicePointStorageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageCollection(array $servicePointUuids): ServicePointStorageCollectionTransfer
    {
        $servicePointStorageConditionsTransfer = (new ServicePointStorageConditionsTransfer())
            ->setUuids($servicePointUuids)
            ->setStoreName($this->storeClient->getCurrentStore()->getNameOrFail());

        $servicePointStorageCriteriaTransfer = (new ServicePointStorageCriteriaTransfer())
            ->setServicePointStorageConditions($servicePointStorageConditionsTransfer);

        return $this->servicePointStorageClient->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);
    }

    /**
     * @param string $servicePointUuid
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageTransfer|null
     */
    public function findServicePointStorage(string $servicePointUuid): ?ServicePointStorageTransfer
    {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ServicePointStorageTransfer> $servicePointStoragesTransfers */
        $servicePointStoragesTransfers = $this->getServicePointStorageCollection([$servicePointUuid])->getServicePointStorages();

        if (!$servicePointStoragesTransfers->count()) {
            return null;
        }

        return $servicePointStoragesTransfers->getIterator()->current();
    }
}
