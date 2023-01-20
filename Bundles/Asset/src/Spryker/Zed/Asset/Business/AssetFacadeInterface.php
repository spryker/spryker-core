<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetCollectionTransfer;
use Generated\Shared\Transfer\AssetCriteriaTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;

interface AssetFacadeInterface
{
    /**
     * Specification:
     * - Creates a new asset entity with new assetStoreEntity relations.
     * - Uses incoming transfer to set entity fields.
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
     * - Returns asset response with newly created asset transfer inside.
     * - Throws InvalidAssetException in case a record is found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function addAsset(AssetAddedTransfer $assetAddedTransfer): AssetTransfer;

    /**
     * Specification:
     * - Finds an asset record by ID in DB.
     * - Uses incoming transfer to update entity fields.
     * - Persists the entity to DB.
     * - Updates a new relations assetStoreEntity.
     * - Returns asset response with updated asset transfer inside.
     * - Throws InvalidAssetException in case a record is not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function updateAsset(AssetUpdatedTransfer $assetUpdatedTransfer): AssetTransfer;

    /**
     * Specification:
     * - Removes an asset record by ID in DB.
     * - Removes related entity assetStoreEntity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void;

    /**
     * Specification:
     * - Gets asset from the database.
     * - Returns AssetTransfer if asset exernal entity exists. Otherwise returns null.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Asset\Business\AssetFacadeInterface::getAssetCollection()} instead.
     *
     * @param int $idAsset
     *
     * @return \Generated\Shared\Transfer\AssetTransfer|null
     */
    public function findAssetById(int $idAsset): ?AssetTransfer;

    /**
     * Specification:
     * - Fetches a collection of assets from the Persistence.
     * - Uses `AssetCriteriaTransfer.pagination.limit` and `AssetCriteriaTransfer.pagination.offset` to paginate results with limit and offset.
     * - Returns `AssetCollectionTransfer` filled with found assets.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetCriteriaTransfer $assetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetCollectionTransfer
     */
    public function getAssetCollection(AssetCriteriaTransfer $assetCriteriaTransfer): AssetCollectionTransfer;
}
