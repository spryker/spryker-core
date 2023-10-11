<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePointStorage\Reader;

use Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer;

interface ServiceTypeReaderInterface
{
    /**
     * @param array<int, string> $uuids
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    public function getServiceTypeStorageCollectionByUuids(array $uuids): ServiceTypeStorageCollectionTransfer;
}
