<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation;

use Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer;

interface CompanyUserInvitationClientInterface
{
    /**
     * Specification:
     * - Imports company user invitations to the persistence
     *
     * @api
     *
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationImportResultTransfer
     */
    public function importInvitations(string $filePath): CompanyUserInvitationImportResultTransfer;
}
