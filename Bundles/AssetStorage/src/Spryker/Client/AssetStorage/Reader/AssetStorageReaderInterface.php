<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetStorage\Reader;

use Generated\Shared\Transfer\AssetStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetStorageCriteriaTransfer;

interface AssetStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetStorageCollectionTransfer
     */
    public function getAssetStorageCollection(
        AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
    ): AssetStorageCollectionTransfer;
}
