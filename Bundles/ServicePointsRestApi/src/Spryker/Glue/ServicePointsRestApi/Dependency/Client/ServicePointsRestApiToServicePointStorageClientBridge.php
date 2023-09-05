<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer;

class ServicePointsRestApiToServicePointStorageClientBridge implements ServicePointsRestApiToServicePointStorageClientInterface
{
    /**
     * @var \Spryker\Client\ServicePointStorage\ServicePointStorageClientInterface
     */
    protected $servicePointStorageClient;

    /**
     * @param \Spryker\Client\ServicePointStorage\ServicePointStorageClientInterface $servicePointStorageClient
     */
    public function __construct($servicePointStorageClient)
    {
        $this->servicePointStorageClient = $servicePointStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer $servicePointStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageCollection(
        ServicePointStorageCriteriaTransfer $servicePointStorageCriteriaTransfer
    ): ServicePointStorageCollectionTransfer {
        return $this->servicePointStorageClient->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    public function getServiceTypeStorageCollection(
        ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer
    ): ServiceTypeStorageCollectionTransfer {
        return $this->servicePointStorageClient->getServiceTypeStorageCollection($serviceTypeStorageCriteriaTransfer);
    }
}
