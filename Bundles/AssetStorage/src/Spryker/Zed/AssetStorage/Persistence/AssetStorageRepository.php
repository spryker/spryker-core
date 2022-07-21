<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\AssetStorage\Persistence\Map\SpyAssetSlotStorageTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStoragePersistenceFactory getFactory()
 */
class AssetStorageRepository extends AbstractRepository implements AssetStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $assetSlotStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationTransferCollection(
        FilterTransfer $filterTransfer,
        array $assetSlotStorageIds
    ): array {
        $assetSlotStorageQuery = $this->getFactory()->createAssetSlotStorageQuery()
            ->orderBy(SpyAssetSlotStorageTableMap::COL_ID_ASSET_SLOT_STORAGE);

        if ($assetSlotStorageIds !== []) {
            $assetSlotStorageQuery->filterByIdAssetSlotStorage_In($assetSlotStorageIds);
        }

        $assetSlotStorageEntityTransfers = $this->buildQueryFromCriteria($assetSlotStorageQuery, $filterTransfer)->find();

        return $this->getFactory()->createAssetStorageMapper()
            ->mapAssetSlotStorageEntityTransfersToSynchronizationTransfers($assetSlotStorageEntityTransfers);
    }

    /**
     * @param string $assetSlot
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\AssetSlotStorageTransfer>
     */
    public function findAssetStoragesByAssetSlotAndStores(string $assetSlot, array $storeNames): array
    {
        $assetSlotStorageEntities = $this->getFactory()
            ->createAssetSlotStorageQuery()
            ->filterByAssetSlot($assetSlot)
            ->filterByStore_In($storeNames)
            ->find();

        return $this->getFactory()
            ->createAssetStorageMapper()
            ->mapAssetSlotStorageEntitiesToTransfers($assetSlotStorageEntities);
    }
}
