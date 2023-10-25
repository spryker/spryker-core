<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageConditionsTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface;

class ServicePointReader implements ServicePointReaderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface
     */
    protected ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient;

    /**
     * @var \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface
     */
    protected ShipmentTypeServicePointsRestApiToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient
     * @param \Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToStoreClientInterface $storeClient
     */
    public function __construct(
        ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface $servicePointStorageClient,
        ShipmentTypeServicePointsRestApiToStoreClientInterface $storeClient
    ) {
        $this->servicePointStorageClient = $servicePointStorageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param list<string> $uuids
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageTransfersByUuids(array $uuids): ServicePointStorageCollectionTransfer
    {
        $storeTransfer = $this->storeClient->getCurrentStore();
        $servicePointStorageCriteriaTransfer = $this->createServicePointStorageCriteriaTransfer($storeTransfer, $uuids);

        return $this->servicePointStorageClient->getServicePointStorageCollection(
            $servicePointStorageCriteriaTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param list<string> $uuids
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer
     */
    protected function createServicePointStorageCriteriaTransfer(StoreTransfer $storeTransfer, array $uuids): ServicePointStorageCriteriaTransfer
    {
        $servicePointStorageConditionsTransfer = (new ServicePointStorageConditionsTransfer())
            ->setStoreName($storeTransfer->getNameOrFail())
            ->setUuids($uuids);

        return (new ServicePointStorageCriteriaTransfer())->setServicePointStorageConditions($servicePointStorageConditionsTransfer);
    }
}
