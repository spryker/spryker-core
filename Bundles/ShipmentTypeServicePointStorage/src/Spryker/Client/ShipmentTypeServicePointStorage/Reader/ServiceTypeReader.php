<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePointStorage\Reader;

use Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer;
use Spryker\Client\ShipmentTypeServicePointStorage\Dependency\Client\ShipmentTypeServicePointStorageToServicePointStorageClientInterface;

class ServiceTypeReader implements ServiceTypeReaderInterface
{
    /**
     * @var \Spryker\Client\ShipmentTypeServicePointStorage\Dependency\Client\ShipmentTypeServicePointStorageToServicePointStorageClientInterface
     */
    protected ShipmentTypeServicePointStorageToServicePointStorageClientInterface $servicePointStorageClient;

    /**
     * @param \Spryker\Client\ShipmentTypeServicePointStorage\Dependency\Client\ShipmentTypeServicePointStorageToServicePointStorageClientInterface $servicePointStorageClient
     */
    public function __construct(ShipmentTypeServicePointStorageToServicePointStorageClientInterface $servicePointStorageClient)
    {
        $this->servicePointStorageClient = $servicePointStorageClient;
    }

    /**
     * @param array<int, string> $uuids
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    public function getServiceTypeStorageCollectionByUuids(array $uuids): ServiceTypeStorageCollectionTransfer
    {
        $serviceTypeStorageCriteriaTransfer = $this->createServiceTypeStorageCriteriaTransfer($uuids);

        return $this->servicePointStorageClient->getServiceTypeStorageCollection($serviceTypeStorageCriteriaTransfer);
    }

    /**
     * @param array<int, string> $uuids
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer
     */
    protected function createServiceTypeStorageCriteriaTransfer(array $uuids): ServiceTypeStorageCriteriaTransfer
    {
        $serviceTypeStorageConditionsTransfer = (new ServiceTypeStorageConditionsTransfer())->setUuids($uuids);

        return (new ServiceTypeStorageCriteriaTransfer())->setServiceTypeStorageConditions($serviceTypeStorageConditionsTransfer);
    }
}
