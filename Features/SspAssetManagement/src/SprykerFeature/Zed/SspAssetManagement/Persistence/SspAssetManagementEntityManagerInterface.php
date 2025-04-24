<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Persistence;

use Generated\Shared\Transfer\SspAssetTransfer;

interface SspAssetManagementEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function createSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function updateSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;

    /**
     * @param int $idSspAsset
     * @param array<int> $businessUnitIds
     *
     * @return void
     */
    public function deleteAssetToCompanyBusinessUnitRelations(int $idSspAsset, array $businessUnitIds): void;

    /**
     * @param int $idSspAsset
     * @param array<int> $businessUnitIds
     *
     * @return void
     */
    public function createAssetToCompanyBusinessUnitRelation(int $idSspAsset, array $businessUnitIds): void;
}
