<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetStorage;

use Generated\Shared\Transfer\AssetStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetStorageCriteriaTransfer;

interface AssetStorageClientInterface
{
    /**
     * Specification:
     * - Gets asset collection transfer object from storage for the specified asset slot and store name combination.
     * - Requires AssetStorageCriteriaTransfer.slotKey transfer field to be set.
     * - Requires AssetStorageCriteriaTransfer.storeName transfer field to be set.
     * - Gets data from storage by key equals asset:{storeName}:{slotKey}.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetStorageCollectionTransfer
     */
    public function getAssetCollection(
        AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
    ): AssetStorageCollectionTransfer;
}
