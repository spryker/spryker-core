<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Mapper;

use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface SspAssetSearchMapperInterface
{
    public function mapSspAssetCollectionTransferToSspAssetSearchCollectionTransfer(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        SspAssetSearchCollectionTransfer $sspAssetSearchCollectionTransfer
    ): SspAssetSearchCollectionTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetSearch> $sspAssetSearchEntities
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function mapSspAssetSearchEntitiesToSynchronizationDataTransfers(ObjectCollection $sspAssetSearchEntities): array;
}
