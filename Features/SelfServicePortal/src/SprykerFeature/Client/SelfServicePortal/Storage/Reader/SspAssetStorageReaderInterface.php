<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Storage\Reader;

use Generated\Shared\Transfer\SspAssetStorageCollectionTransfer;
use Generated\Shared\Transfer\SspAssetStorageCriteriaTransfer;

interface SspAssetStorageReaderInterface
{
    public function getSspAssetStorageCollection(
        SspAssetStorageCriteriaTransfer $sspAssetStorageCriteriaTransfer
    ): SspAssetStorageCollectionTransfer;
}
