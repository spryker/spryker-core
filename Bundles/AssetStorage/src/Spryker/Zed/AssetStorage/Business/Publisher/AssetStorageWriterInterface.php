<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Business\Publisher;

interface AssetStorageWriterInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeAssetCollectionByAssetEvents(array $eventEntityTransfers): void;

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteAssetCollectionByAssetEvents(array $eventEntityTransfers): void;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     *
     * @return void
     */
    public function publish(int $idAsset): void;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAsset, int $idStore): void;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     *
     * @return void
     */
    public function unpublish(int $idAsset): void;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAsset, int $idStore): void;
}
