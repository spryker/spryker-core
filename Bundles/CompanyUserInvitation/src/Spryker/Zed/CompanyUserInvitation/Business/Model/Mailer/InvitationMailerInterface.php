<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Mailer;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;

interface InvitationMailerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return void
     */
    public function mailInvitation(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): void;
}
