<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;

interface ServicePointReaderInterface
{
    /**
     * @param list<string> $uuids
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageTransfersByUuids(array $uuids): ServicePointStorageCollectionTransfer;
}
