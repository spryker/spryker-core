<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ServicePointStorage\ServicePointStorageFactory getFactory()
 */
class ServicePointStorageClient extends AbstractClient implements ServicePointStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer $servicePointStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageCollection(
        ServicePointStorageCriteriaTransfer $servicePointStorageCriteriaTransfer
    ): ServicePointStorageCollectionTransfer {
        return $this->getFactory()
            ->createServicePointStorageReader()
            ->getServicePointStorageCollection($servicePointStorageCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    public function getServiceTypeStorageCollection(
        ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer
    ): ServiceTypeStorageCollectionTransfer {
        return $this->getFactory()
            ->createServiceTypeStorageReader()
            ->getServiceTypeStorageCollection($serviceTypeStorageCriteriaTransfer);
    }
}
