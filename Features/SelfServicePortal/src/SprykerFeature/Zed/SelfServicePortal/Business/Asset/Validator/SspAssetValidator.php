<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\CreateSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\UnassignSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\UpdateSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;

class SspAssetValidator implements SspAssetValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const MESSAGE_ASSET_CREATION_ACCESS_DENIED = 'self_service_portal.asset.access.denied';

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return bool
     */
    public function isCompanyUserGrantedToApplyCriteria(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): bool
    {
        $companyUserTransfer = $sspAssetCriteriaTransfer->getCompanyUser();

        // when company user is not provided, assume that the asset is being updated by a system user
        if (!$companyUserTransfer) {
            return true;
        }

        return $this->isCompanyUserAllowedToViewAsset($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function validateRequestGrantedToCreateAsset(
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer,
        ?CompanyUserTransfer $companyUserTransfer
    ): SspAssetCollectionResponseTransfer {
        // when company user is not provided, assume that the asset is being updated by a system user
        if (!$companyUserTransfer) {
            return $sspAssetCollectionResponseTransfer;
        }

        if (
            !$this->can(
                CreateSspAssetPermissionPlugin::KEY,
                $companyUserTransfer->getIdCompanyUserOrFail(),
            )
        ) {
            return $sspAssetCollectionResponseTransfer->addError(
                (new ErrorTransfer())->setMessage(static::MESSAGE_ASSET_CREATION_ACCESS_DENIED),
            );
        }

        return $sspAssetCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
     *
     * @return bool
     */
    public function validateAssetTransfer(
        SspAssetTransfer $sspAssetTransfer,
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
    ): bool {
        if (!$sspAssetTransfer->getName()) {
            $sspAssetCollectionResponseTransfer->addError(
                (new ErrorTransfer())->setMessage('self_service_portal.asset.validation.name.not_set'),
            );

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return bool
     */
    public function isAssetUpdateGranted(
        SspAssetTransfer $sspAssetTransfer,
        ?CompanyUserTransfer $companyUserTransfer
    ): bool {
//      when company user is not provided, assume that the asset is being updated by a system user
        if (!$companyUserTransfer) {
            return true;
        }

        $permissionCheckContext = [
            ViewCompanySspAssetPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewCompanySspAssetPermissionPlugin::CONTEXT_SSP_ASSET => $sspAssetTransfer,
        ];

        if (
            $this->isCompanyUserAllowedToViewAsset($companyUserTransfer, $permissionCheckContext) &&
            $this->can(UpdateSspAssetPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer|null $companyUserTransfer
     *
     * @return bool
     */
    public function isUnassignBusinessUnitFromAssetGranted(
        SspAssetTransfer $sspAssetTransfer,
        ?CompanyUserTransfer $companyUserTransfer
    ): bool {
//      when company user is not provided, assume that the asset is being updated by a system user
        if (!$companyUserTransfer) {
            return true;
        }

        $permissionCheckContext = [
            ViewCompanySspAssetPermissionPlugin::CONTEXT_COMPANY_USER => $companyUserTransfer,
            ViewCompanySspAssetPermissionPlugin::CONTEXT_SSP_ASSET => $sspAssetTransfer,
        ];

        if (
            $this->isCompanyUserAllowedToViewAsset($companyUserTransfer, $permissionCheckContext) &&
            $this->can(UnassignSspAssetPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail())
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param array<string, mixed> $permissionCheckContext
     *
     * @return bool
     */
    protected function isCompanyUserAllowedToViewAsset(CompanyUserTransfer $companyUserTransfer, array $permissionCheckContext = []): bool
    {
        return ($this->can(ViewCompanySspAssetPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail(), $permissionCheckContext) ||
            $this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, $companyUserTransfer->getIdCompanyUserOrFail(), $permissionCheckContext));
    }
}
