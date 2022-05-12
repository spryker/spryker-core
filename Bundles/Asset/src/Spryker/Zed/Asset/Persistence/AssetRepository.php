<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Persistence;

use Generated\Shared\Transfer\AssetTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Asset\Persistence\AssetPersistenceFactory getFactory()
 */
class AssetRepository extends AbstractRepository implements AssetRepositoryInterface
{
    /**
     * @param string $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetTransfer|null
     */
    public function findAssetByAssetUuid(string $assetUuid): ?AssetTransfer
    {
        $assetEntity = $this->getFactory()->createAssetQuery()
            ->filterByAssetUuid($assetUuid)
            ->leftJoinWithSpyAssetStore()
            ->useSpyAssetStoreQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyStore()
            ->endUse()
            ->find()
            ->getFirst();

        if ($assetEntity === null) {
            return null;
        }

        return $this->getFactory()->createAssetMapper()
            ->mapAssetEntityToAssetTransfer($assetEntity, new AssetTransfer());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     *
     * @return \Generated\Shared\Transfer\AssetTransfer|null
     */
    public function findAssetById(int $idAsset): ?AssetTransfer
    {
        $assetEntity = $this->getFactory()->createAssetQuery()
            ->findOneByIdAsset($idAsset);

        if ($assetEntity === null) {
            return null;
        }

        return $this->getFactory()->createAssetMapper()
            ->mapAssetEntityToAssetTransfer($assetEntity, new AssetTransfer());
    }
}
