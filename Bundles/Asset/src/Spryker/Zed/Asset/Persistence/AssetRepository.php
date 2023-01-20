<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Persistence;

use Generated\Shared\Transfer\AssetCollectionTransfer;
use Generated\Shared\Transfer\AssetCriteriaTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Asset\Persistence\SpyAssetQuery;
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

    /**
     * @param \Generated\Shared\Transfer\AssetCriteriaTransfer $assetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetCollectionTransfer
     */
    public function getAssetCollection(AssetCriteriaTransfer $assetCriteriaTransfer): AssetCollectionTransfer
    {
        $assetCollectionTransfer = new AssetCollectionTransfer();
        $assetQuery = $this->getFactory()->createAssetQuery();

        $assetQuery = $this->applyAssetFilters($assetQuery, $assetCriteriaTransfer);

        $paginationTransfer = $assetCriteriaTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $assetQuery = $this->applyAssetPagination($assetQuery, $paginationTransfer);
            $assetCollectionTransfer->setPagination($paginationTransfer);
        }

        return $this->getFactory()
            ->createAssetMapper()
            ->mapAssetEntitiesToAssetCollectionTransfer($assetQuery->find(), $assetCollectionTransfer);
    }

    /**
     * @param \Orm\Zed\Asset\Persistence\SpyAssetQuery $assetQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Orm\Zed\Asset\Persistence\SpyAssetQuery
     */
    protected function applyAssetPagination(SpyAssetQuery $assetQuery, PaginationTransfer $paginationTransfer): SpyAssetQuery
    {
        $paginationTransfer->setNbResults($assetQuery->count());
        if ($paginationTransfer->getLimit() !== null && $paginationTransfer->getOffset() !== null) {
            return $assetQuery
                ->limit($paginationTransfer->getLimitOrFail())
                ->offset($paginationTransfer->getOffsetOrFail());
        }

        return $assetQuery;
    }

    /**
     * @param \Orm\Zed\Asset\Persistence\SpyAssetQuery $assetQuery
     * @param \Generated\Shared\Transfer\AssetCriteriaTransfer $assetCriteriaTransfer
     *
     * @return \Orm\Zed\Asset\Persistence\SpyAssetQuery
     */
    protected function applyAssetFilters(SpyAssetQuery $assetQuery, AssetCriteriaTransfer $assetCriteriaTransfer): SpyAssetQuery
    {
        $assetConditionsTransfer = $assetCriteriaTransfer->getAssetConditions();

        if ($assetConditionsTransfer === null) {
            return $assetQuery;
        }

        if ($assetConditionsTransfer->getAssetIds()) {
            $assetQuery->filterByIdAsset_In($assetConditionsTransfer->getAssetIds());
        }

        return $assetQuery;
    }
}
