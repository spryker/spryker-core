<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission;

use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;

interface FileAttachmentPermissionCheckerInterface
{
    public function isCompanyUserGrantedToApplyCriteria(FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer): bool;
}
