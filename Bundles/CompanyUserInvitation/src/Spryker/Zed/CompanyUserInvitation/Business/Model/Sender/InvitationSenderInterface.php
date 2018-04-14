<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Sender;

use Generated\Shared\Transfer\CompanyUserInvitationSendBatchResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface InvitationSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendResultTransfer
     */
    public function sendCompanyUserInvitation(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationSendResultTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationSendBatchResultTransfer
     */
    public function sendCompanyUserInvitations(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserInvitationSendBatchResultTransfer;
}
