<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Extractor;

use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServiceStorageCollectionTransfer;

interface ServicePointStorageExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceStorageCollectionTransfer
     */
    public function extractServiceStorageCollectionFromServicePointStorageCollectionTransfer(
        ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
    ): ServiceStorageCollectionTransfer;
}
