<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Permission;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;

class SspAssetCustomerPermissionChecker implements SspAssetCustomerPermissionCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer|null $sspAssetTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return bool
     */
    public function canViewAsset(?SspAssetTransfer $sspAssetTransfer = null, ?CompanyUserTransfer $companyUserTransfer = null): bool
    {
        $canViewCompanySspAssets = $this->can(ViewCompanySspAssetPermissionPlugin::KEY, [
            ViewCompanySspAssetPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewCompanySspAssetPermissionPlugin::CONTEXT_SSP_ASSET => $sspAssetTransfer,
        ]);

        $canViewBusinessUnitSspAssets = $this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, [
            ViewCompanySspAssetPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewCompanySspAssetPermissionPlugin::CONTEXT_SSP_ASSET => $sspAssetTransfer,
        ]);

        return $canViewCompanySspAssets || $canViewBusinessUnitSspAssets;
    }
}
