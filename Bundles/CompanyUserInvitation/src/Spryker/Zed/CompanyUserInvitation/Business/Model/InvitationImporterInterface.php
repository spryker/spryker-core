<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model;

use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;

interface InvitationImporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer $importRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importInvitations(CompanyUserInvitationImportRequestTransfer $importRequestTransfer): CompanyUserInvitationImportResultTransfer;
}
