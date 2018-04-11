<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Importer;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer;

interface InvitationImporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportReportTransfer
     */
    public function importInvitations(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
    ): CompanyUserInvitationImportReportTransfer;
}
