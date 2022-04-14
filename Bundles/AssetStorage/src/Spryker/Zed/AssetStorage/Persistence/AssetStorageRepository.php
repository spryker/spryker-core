<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStoragePersistenceFactory getFactory()
 */
class AssetStorageRepository extends AbstractRepository implements AssetStorageRepositoryInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStorages(): array
    {
        $assetSlotStorageEntities = $this->getFactory()
            ->createAssetStorageQuery()
            ->find();

        return $this->getFactory()
            ->createAssetStorageMapper()
            ->mapAssetSlotStorageEntitiesToAssetSlotStorageEntityTransfers($assetSlotStorageEntities);
    }

    /**
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStoragesByAssetIds(array $ids): array
    {
        $query = $this->getFactory()->createAssetStorageQuery();

        if ($ids !== []) {
            $query->filterByIdAssetSlotStorage_In($ids);
        }

        $assetSlotStorageEntities = $query->find();

        return $this
            ->getFactory()
            ->createAssetStorageMapper()
            ->mapAssetSlotStorageEntitiesToAssetSlotStorageEntityTransfers($assetSlotStorageEntities);
    }

    /**
     * @param string $assetSlot
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStoragesByAssetSlot(string $assetSlot): array
    {
        $assetSlotStorageEntities = $this->getFactory()
            ->createAssetStorageQuery()
            ->filterByAssetSlot($assetSlot)
            ->find();

        return $this->getFactory()
            ->createAssetStorageMapper()
            ->mapAssetSlotStorageEntitiesToAssetSlotStorageEntityTransfers($assetSlotStorageEntities);
    }

    /**
     * @param string $assetSlot
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStoragesWithAssetSlotNotEqualAndByStores(string $assetSlot, array $storeNames): array
    {
        $assetSlotStorageEntities = $this->getFactory()
            ->createAssetStorageQuery()
            ->filterByAssetSlot($assetSlot, Criteria::NOT_EQUAL)
            ->filterByStore_In($storeNames)
            ->find();

        return $this->getFactory()
            ->createAssetStorageMapper()
            ->mapAssetSlotStorageEntitiesToAssetSlotStorageEntityTransfers($assetSlotStorageEntities);
    }

    /**
     * @param string $assetSlot
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function findAssetStoragesByAssetSlotAndStores(string $assetSlot, array $storeNames): array
    {
        $assetSlotStorageEntities = $this->getFactory()
            ->createAssetStorageQuery()
            ->filterByAssetSlot($assetSlot)
            ->filterByStore_In($storeNames)
            ->find();

        return $this->getFactory()
            ->createAssetStorageMapper()
            ->mapAssetSlotStorageEntitiesToAssetSlotStorageEntityTransfers($assetSlotStorageEntities);
    }
}
