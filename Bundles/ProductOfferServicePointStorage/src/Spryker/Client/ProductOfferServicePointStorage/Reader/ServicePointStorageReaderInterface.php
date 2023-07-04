<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Reader;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;

interface ServicePointStorageReaderInterface
{
    /**
     * @param list<string> $servicePointUuids
     *
     * @return \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer
     */
    public function getServicePointStorageCollectionByServicePointUuids(
        array $servicePointUuids
    ): ServicePointStorageCollectionTransfer;
}
