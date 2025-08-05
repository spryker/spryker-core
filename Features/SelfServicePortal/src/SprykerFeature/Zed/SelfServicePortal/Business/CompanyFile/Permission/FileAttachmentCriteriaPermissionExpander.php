<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission;

use Generated\Shared\Transfer\FileAttachmentConditionsTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;

class FileAttachmentCriteriaPermissionExpander implements FileAttachmentPermissionExpanderInterface
{
    use PermissionAwareTrait;

    public function expand(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): FileAttachmentCriteriaTransfer
    {
        if (!$fileAttachmentCriteriaTransfer->getCompanyUser()) {
            return $fileAttachmentCriteriaTransfer;
        }

        if (!$fileAttachmentCriteriaTransfer->getFileAttachmentConditions()) {
            $fileAttachmentCriteriaTransfer->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());
        }

        $fileAttachmentCriteriaTransfer = $this->expandWithBusinessAttachmentPermissions($fileAttachmentCriteriaTransfer);

        $fileAttachmentCriteriaTransfer = $this->expandWithSspAssetPermissions($fileAttachmentCriteriaTransfer);

        return $fileAttachmentCriteriaTransfer;
    }

    protected function expandWithBusinessAttachmentPermissions(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCriteriaTransfer {
        $companyUserTransfer = $fileAttachmentCriteriaTransfer->getCompanyUserOrFail();

        if (!$fileAttachmentCriteriaTransfer->getFileAttachmentConditions()) {
            $fileAttachmentCriteriaTransfer->setFileAttachmentConditions(new FileAttachmentConditionsTransfer());
        }

        $fileAttachmentConditions = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail();

        $idCompanyUser = $companyUserTransfer->getIdCompanyUserOrFail();

        $fileAttachmentConditions->setCompanyIds([]);
        if ($this->can(ViewCompanyFilesPermissionPlugin::KEY, $idCompanyUser)) {
            $fileAttachmentConditions->addCompanyId(
                $companyUserTransfer->getCompanyOrFail()->getIdCompanyOrFail(),
            );

            return $fileAttachmentCriteriaTransfer;
        }

        $fileAttachmentCriteriaTransfer->setWithCompanyRelation(false);

        $fileAttachmentConditions->setBusinessUnitIds([]);
        if ($this->can(ViewCompanyBusinessUnitFilesPermissionPlugin::KEY, $idCompanyUser)) {
            $fileAttachmentConditions->addBusinessUnitId(
                $companyUserTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
            );

            return $fileAttachmentCriteriaTransfer;
        }

        $fileAttachmentCriteriaTransfer->setWithBusinessUnitRelation(false);

        $fileAttachmentConditions->setCompanyUserIds([]);
        if ($this->can(ViewCompanyUserFilesPermissionPlugin::KEY, $idCompanyUser)) {
            $fileAttachmentConditions->addCompanyUserId(
                $companyUserTransfer->getIdCompanyUserOrFail(),
            );

            return $fileAttachmentCriteriaTransfer;
        }

        $fileAttachmentCriteriaTransfer->setWithCompanyUserRelation(false);

        return $fileAttachmentCriteriaTransfer;
    }

    protected function expandWithSspAssetPermissions(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCriteriaTransfer {
        $companyUserTransfer = $fileAttachmentCriteriaTransfer->getCompanyUserOrFail();

        $fileAttachmentConditions = $fileAttachmentCriteriaTransfer->getFileAttachmentConditionsOrFail();

        $idCompanyUser = $companyUserTransfer->getIdCompanyUserOrFail();

        $fileAttachmentConditions->setSspAssetCompanyIds([]);
        if ($this->can(ViewCompanySspAssetPermissionPlugin::KEY, $idCompanyUser)) {
            $fileAttachmentConditions->addSspAssetCompanyId(
                $companyUserTransfer->getCompanyOrFail()->getIdCompanyOrFail(),
            );
        }

        $fileAttachmentConditions->setSspAssetBusinessUnitIds([]);
        if ($this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, $idCompanyUser)) {
            $fileAttachmentConditions->addSspAssetBusinessUnitId($companyUserTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail());
        }

        if (!$this->can(ViewCompanySspAssetPermissionPlugin::KEY, $idCompanyUser) && !$this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, $idCompanyUser)) {
            $fileAttachmentCriteriaTransfer->setWithSspAssetRelation(false);
        }

        return $fileAttachmentCriteriaTransfer;
    }
}
