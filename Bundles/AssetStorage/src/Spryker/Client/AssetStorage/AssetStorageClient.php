<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetStorage;

use Generated\Shared\Transfer\AssetStorageCollectionTransfer;
use Generated\Shared\Transfer\AssetStorageCriteriaTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\AssetStorage\AssetStorageFactory getFactory()
 */
class AssetStorageClient extends AbstractClient implements AssetStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetStorageCollectionTransfer
     */
    public function getAssetCollection(
        AssetStorageCriteriaTransfer $assetStorageCriteriaTransfer
    ): AssetStorageCollectionTransfer {
        return $this->getFactory()
            ->createAssetStorageReader()
            ->getAssetStorageCollection($assetStorageCriteriaTransfer);
    }
}
