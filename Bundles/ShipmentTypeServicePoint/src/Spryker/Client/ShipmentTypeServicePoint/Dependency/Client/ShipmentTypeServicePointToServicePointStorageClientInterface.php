<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePoint\Dependency\Client;

use Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer;

interface ShipmentTypeServicePointToServicePointStorageClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    public function getServiceTypeStorageCollection(
        ServiceTypeStorageCriteriaTransfer $serviceTypeStorageCriteriaTransfer
    ): ServiceTypeStorageCollectionTransfer;
}
