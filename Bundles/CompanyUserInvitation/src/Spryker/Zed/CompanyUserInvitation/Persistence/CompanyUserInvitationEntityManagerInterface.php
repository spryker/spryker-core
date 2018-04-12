<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Persistence;

use Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;

interface CompanyUserInvitationEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function saveCompanyUserInvitation(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer $companyUserInvitationStatusTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationStatusTransfer
     */
    public function saveCompanyUserInvitationStatus(
        CompanyUserInvitationStatusTransfer $companyUserInvitationStatusTransfer
    ): CompanyUserInvitationStatusTransfer;
}
