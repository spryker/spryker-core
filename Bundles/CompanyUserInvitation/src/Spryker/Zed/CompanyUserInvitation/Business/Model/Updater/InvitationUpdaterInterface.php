<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Updater;

use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer;

interface InvitationUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusResponseTransfer
     */
    public function updateStatus(
        CompanyUserInvitationUpdateStatusRequestTransfer $companyUserInvitationUpdateStatusRequestTransfer
    ): CompanyUserInvitationUpdateStatusResponseTransfer;
}
