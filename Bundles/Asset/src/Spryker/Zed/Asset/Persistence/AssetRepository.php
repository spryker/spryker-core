<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Persistence;

use Generated\Shared\Transfer\AssetTransfer;
use Orm\Zed\Asset\Persistence\SpyAsset;
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
        $assetEntity = $this->getFactory()
            ->createAssetQuery()
            ->filterByAssetUuid($assetUuid)
            ->findOne();

        if ($assetEntity === null) {
            return null;
        }

        return $this->getAssetTransfer($assetEntity);
    }

    /**
     * @param int $idAsset
     *
     * @return \Generated\Shared\Transfer\AssetTransfer|null
     */
    public function findAssetById(int $idAsset): ?AssetTransfer
    {
        $assetEntity = $this->getFactory()
            ->createAssetQuery()
            ->filterByIdAsset($idAsset)
            ->findOne();

        if ($assetEntity === null) {
            return null;
        }

        return $this->getAssetTransfer($assetEntity);
    }

    /**
     * @param \Orm\Zed\Asset\Persistence\SpyAsset $assetEntity
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    protected function getAssetTransfer(SpyAsset $assetEntity): AssetTransfer
    {
        /** @var \Generated\Shared\Transfer\AssetTransfer $assetTransfer */
        $assetTransfer = $this->getFactory()
            ->createAssetMapper()
            ->mapAssetEntityToAssetTransfer($assetEntity);

        $assetStoreEntities = $this->getFactory()
            ->createAssetStoreQuery()
            ->joinWithSpyStore(Criteria::LEFT_JOIN)
            ->filterByFkAsset($assetEntity->getIdAsset())
            ->find();

        return $assetTransfer;
    }
}
