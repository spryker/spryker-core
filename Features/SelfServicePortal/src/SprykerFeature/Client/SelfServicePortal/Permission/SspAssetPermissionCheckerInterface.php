<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Permission;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface SspAssetPermissionCheckerInterface
{
    /**
     * @param array<string, mixed> $storageData
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function canViewSspAsset(array $storageData, CompanyUserTransfer $companyUserTransfer): bool;
}
