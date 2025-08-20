<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Storage\Mapper;

use Generated\Shared\Transfer\SspAssetStorageTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;

interface SspAssetStorageMapperInterface
{
    /**
     * @param array<string, mixed> $storageData
     *
     * @return \Generated\Shared\Transfer\SspAssetStorageTransfer
     */
    public function mapStorageDataToSspAssetStorageTransfer(array $storageData): SspAssetStorageTransfer;

    /**
     * @param array<string, mixed> $storageData
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function mapStorageDataToSspAssetTransferWithCompanyAssignmentsOnly(array $storageData, SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;

    /**
     * @param array<string, mixed> $storageData
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function mapStorageDataToSspAssetTransferWithBusinessUnitAssignmentsOnly(array $storageData, SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;
}
