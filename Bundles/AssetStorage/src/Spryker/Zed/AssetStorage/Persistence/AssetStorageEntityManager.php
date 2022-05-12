<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence;

use Generated\Shared\Transfer\AssetSlotStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStoragePersistenceFactory getFactory()
 */
class AssetStorageEntityManager extends AbstractEntityManager implements AssetStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetSlotStorageTransfer $assetSlotStorageTransfer
     *
     * @return void
     */
    public function saveAssetSlotStorage(AssetSlotStorageTransfer $assetSlotStorageTransfer): void
    {
        $assetSlotStorageEntity = $this->getFactory()->createAssetSlotStorageQuery()
            ->filterByIdAssetSlotStorage($assetSlotStorageTransfer->getIdAssetSlotStorage())
            ->findOneOrCreate();

        $assetSlotStorageEntity = $this->getFactory()->createAssetStorageMapper()
            ->mapAssetSlotStorageTransferToEntity($assetSlotStorageTransfer, $assetSlotStorageEntity);
        $assetSlotStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\AssetSlotStorageTransfer $assetSlotStorageTransfer
     *
     * @return void
     */
    public function deleteAssetSlotStorage(AssetSlotStorageTransfer $assetSlotStorageTransfer): void
    {
        $assetSlotStorageEntity = $this->getFactory()->createAssetSlotStorageQuery()
            ->filterByIdAssetSlotStorage($assetSlotStorageTransfer->getIdAssetSlotStorage())
            ->findOne();

        if (!$assetSlotStorageEntity) {
            return;
        }

        $assetSlotStorageEntity->delete();
    }
}
