<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Storage;

interface SspAssetStorageWriterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeSspAssetStorageCollectionBySspAssetEvents(array $eventEntityTransfers): void;

    /**
     * @param array<int> $sspAssetIds
     *
     * @return void
     */
    public function writeSspAssetStorageCollection(array $sspAssetIds): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeSspAssetStorageCollectionBySspAssetToCompanyBusinessUnitEvents(array $eventEntityTransfers): void;

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeSspAssetStorageCollectionBySspAssetToModelEvents(array $eventEntityTransfers): void;
}
