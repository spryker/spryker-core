<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeServicePoint\Reader;

use Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer;

interface ServiceTypeReaderInterface
{
    /**
     * @param list<string> $uuids
     *
     * @return \Generated\Shared\Transfer\ServiceTypeStorageCollectionTransfer
     */
    public function getServiceTypeStorageCollectionByUuids(array $uuids): ServiceTypeStorageCollectionTransfer;
}
