<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission;

use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;

class FileAttachmentPermissionChecker implements FileAttachmentPermissionCheckerInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return bool
     */
    public function isCompanyUserGrantedToApplyCriteria(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): bool
    {
        if ($fileAttachmentCriteriaTransfer->getUser()) {
            return true;
        }

        $companyUserId = $fileAttachmentCriteriaTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail();

        if ($fileAttachmentCriteriaTransfer->getWithCompanyRelation() && $this->can(ViewCompanyFilesPermissionPlugin::KEY, $companyUserId)) {
            return true;
        }

        if (
            $fileAttachmentCriteriaTransfer->getWithBusinessUnitRelation() &&
            (
                $this->can(ViewCompanyFilesPermissionPlugin::KEY, $companyUserId) ||
                $this->can(ViewCompanyBusinessUnitFilesPermissionPlugin::KEY, $companyUserId)
            )
        ) {
            return true;
        }

        if (
            $fileAttachmentCriteriaTransfer->getWithCompanyUserRelation() &&
            (
                $this->can(ViewCompanyUserFilesPermissionPlugin::KEY, $companyUserId) ||
                $this->can(ViewCompanyBusinessUnitFilesPermissionPlugin::KEY, $companyUserId) ||
                $this->can(ViewCompanyFilesPermissionPlugin::KEY, $companyUserId)
            )
        ) {
            return true;
        }

        if (
            $fileAttachmentCriteriaTransfer->getWithSspAssetRelation() &&
            (
                $this->can(ViewCompanySspAssetPermissionPlugin::KEY, $companyUserId) ||
                $this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, $companyUserId)
            )
        ) {
            return true;
        }

        return false;
    }
}
