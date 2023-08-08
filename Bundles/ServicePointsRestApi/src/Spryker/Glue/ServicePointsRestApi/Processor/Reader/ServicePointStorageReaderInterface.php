<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;

interface ServicePointStorageReaderInterface
{
    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageCollection(array $servicePointUuids): ServicePointStorageCollectionTransfer;

    /**
     * @param string $servicePointUuid
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageTransfer|null
     */
    public function findServicePointStorage(string $servicePointUuid): ?ServicePointStorageTransfer;
}
