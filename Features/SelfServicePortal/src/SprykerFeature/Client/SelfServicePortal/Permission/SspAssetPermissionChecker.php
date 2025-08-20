<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Permission;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Client\Permission\PermissionClientInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Mapper\SspAssetStorageMapperInterface;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;

class SspAssetPermissionChecker implements SspAssetPermissionCheckerInterface
{
    public function __construct(
        protected PermissionClientInterface $permissionClient,
        protected SspAssetStorageMapperInterface $sspAssetStorageMapper
    ) {
    }

    /**
     * @param array<string, mixed> $storageData
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    public function canViewSspAsset(array $storageData, CompanyUserTransfer $companyUserTransfer): bool
    {
        return $this->isBusinessUnitPermissionGranted($storageData, $companyUserTransfer)
            || $this->isCompanyPermissionGranted($storageData, $companyUserTransfer);
    }

    /**
     * @param array<string, mixed> $storageData
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    protected function isBusinessUnitPermissionGranted(array $storageData, CompanyUserTransfer $companyUserTransfer): bool
    {
        $sspAssetTransfer = $this->sspAssetStorageMapper->mapStorageDataToSspAssetTransferWithBusinessUnitAssignmentsOnly(
            $storageData,
            new SspAssetTransfer(),
        );

        $permissionCheckContext = [
            ViewBusinessUnitSspAssetPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewBusinessUnitSspAssetPermissionPlugin::CONTEXT_SSP_ASSET => $sspAssetTransfer,
        ];

        return $this->permissionClient->can(
            ViewBusinessUnitSspAssetPermissionPlugin::KEY,
            $permissionCheckContext,
        );
    }

    /**
     * @param array<string, mixed> $storageData
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    protected function isCompanyPermissionGranted(array $storageData, CompanyUserTransfer $companyUserTransfer): bool
    {
        $sspAssetTransfer = $this->sspAssetStorageMapper->mapStorageDataToSspAssetTransferWithCompanyAssignmentsOnly(
            $storageData,
            new SspAssetTransfer(),
        );

        $permissionCheckContext = [
            ViewCompanySspAssetPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewCompanySspAssetPermissionPlugin::CONTEXT_SSP_ASSET => $sspAssetTransfer,
        ];

        return $this->permissionClient->can(
            ViewCompanySspAssetPermissionPlugin::KEY,
            $permissionCheckContext,
        );
    }
}
