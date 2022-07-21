<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Business;

interface AssetStorageFacadeInterface
{
    /**
     * Specification:
     * - Passes through `EventEntity` transfers to get the Assets to publish.
     * - Processes found `Asset` transfers one by one.
     * - Finds all existing AssetSlotStorage entries using `Asset.assetSlot` and Asset.stores`.
     * - Updates the found entries by adding or updating Asset data matching `Asset.idAsset`.
     * - Creates new AssetSlotStorage entry if there's no yet.
     * - Saves change to the database.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeAssetCollectionByAssetEvents(array $eventEntityTransfers): void;

    /**
     *  Specification:
     * - Passes through `EventEntity` transfers to get the Assets to un-publish.
     * - Processes found `Asset` transfers one by one.
     * - Finds all existing AssetSlotStorage entries using `Asset.assetSlot` and Asset.stores`.
     * - Updates the found entries by removing Asset data matching `Asset.idAsset`.
     * - Saves change to the database if there is Asset data left in the entry.
     * - Deletes the entry otherwise.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteAssetCollectionByAssetEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Queries all assets with asset slot equals to requested asset slot.
     * - Stores data as json encoded to storage table per asset slot and store.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     *
     * @return void
     */
    public function publish(int $idAsset): void;

    /**
     * Specification:
     * - Queries all assets with asset slot equals to requested asset csm slot and with store equals to requested store.
     * - Removes asset from json encoded data.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAsset, int $idStore): void;

    /**
     * Specification:
     * - Queries all asset storages with asset slot equals to asset slot from requested asset.
     * - Removes asset from json encoded data.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     *
     * @return void
     */
    public function unpublish(int $idAsset): void;

    /**
     * Specification:
     * - Queries all assets with asset slot equals to requested asset csm slot and with store equals to requested store.
     * - Removes asset from json encoded data.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAsset, int $idStore): void;
}
