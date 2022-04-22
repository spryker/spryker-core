<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Persistence\Mapper;

use Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class AssetStorageMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorage> $assetSlotStorageEntities
     *
     * @return array<\Generated\Shared\Transfer\SpyAssetSlotStorageEntityTransfer>
     */
    public function mapAssetSlotStorageEntitiesToAssetSlotStorageEntityTransfers(ObjectCollection $assetSlotStorageEntities): array
    {
        $assetSlotStorageEntityTransfers = [];
        foreach ($assetSlotStorageEntities as $assetSlotStorageEntity) {
            $assetSlotStorageEntityTransfers[] = (new SpyAssetSlotStorageEntityTransfer())->fromArray($assetSlotStorageEntity->toArray(), true);
        }

        return $assetSlotStorageEntityTransfers;
    }
}
