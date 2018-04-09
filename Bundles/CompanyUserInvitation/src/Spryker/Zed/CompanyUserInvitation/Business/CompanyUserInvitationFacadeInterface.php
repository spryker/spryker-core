<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business;

use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;

interface CompanyUserInvitationFacadeInterface
{
    /**
     * Specification:
     * - Imports company user invitations to the persistence
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importInvitations(CompanyUserInvitationImportRequestTransfer $companyUserInvitationImportRequestTransfer): CompanyUserInvitationImportResultTransfer;
}
