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
     * - Does nothing if the `AssetAddedTransfer.messageAttributes.timestamp` value is not null and is older than the current `spy_asset.last_message_timestamp`.
     * - Creates a new asset entity with new assetStoreEntity relations.
     * - Uses incoming transfer to set entity fields.
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
     * - Returns asset response with newly created asset transfer inside.
     * - If an asset with the same UUID is found, the asset is updated instead.
     * - Requires `AssetAddedTransfer.messageAttributes.storeReference` to be set.
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
     * - Does nothing if the `AssetUpdatedTransfer.messageAttributes.timestamp` value is not null and is older than the current `spy_asset.last_message_timestamp`.
     * - Finds an asset record by UUID in DB.
     * - Uses incoming transfer to update entity fields.
     * - Persists the entity to DB.
     * - Updates a new relations assetStoreEntity.
     * - Returns asset response with updated asset transfer inside.
     * - If an asset with the same UUID is not found, a new asset is created instead.
     * - Requires `AssetUpdatedTransfer.messageAttributes.storeReference` to be set.
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
     * - Does nothing if the `AssetDeletedTransfer.messageAttributes.timestamp` value is not null and is older than the current `spy_asset.last_message_timestamp`.
     * - Deletes relation assetStoreEntity.
     * - If an asset with the same UUID is found in DB and the `is_active` column exists in `spy_asset` table then the asset is soft deleted.
     * - If an asset with the same UUID is found in DB and the `is_active` column does not exist in the `spy_asset` table then the asset is deleted.
     * - If an asset with the same UUID is not found in DB and the `is_active` column exists in `spy_asset` table a new soft deleted asset is created.
     * - Requires `AssetDeletedTransfer.messageAttributes.storeReference` to be set
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
