<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointStorage;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer;

interface ServicePointStorageClientInterface
{
    /**
     * Specification:
     * - Requires `ServicePointStorageCriteriaTransfer.servicePointStorageConditions` to be set.
     * - Requires `ServicePointStorageCriteriaTransfer.servicePointStorageConditions.storeName` to be set.
     * - Retrieves service point storage data filtered by criteria from Storage.
     * - Uses `ServicePointStorageCriteriaTransfer.servicePointStorageConditions.servicePointIds` to filter by service point IDs.
     * - Uses `ServicePointStorageCriteriaTransfer.servicePointStorageConditions.uuids` to filter by service point uuids.
     * - Can filter either by `servicePointIds` or `uuids` at the same time.
     * - Returns `ServicePointStorageCollectionTransfer` filled with found service points.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ServicePointStorageCriteriaTransfer $servicePointStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageCollection(
        ServicePointStorageCriteriaTransfer $servicePointStorageCriteriaTransfer
    ): ServicePointStorageCollectionTransfer;
}
