<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Deleter;

use Generated\Shared\Transfer\CompanyUserInvitationDeleteRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationDeleteResponseTransfer;

interface InvitationDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationDeleteRequestTransfer $companyUserInvitationDeleteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationDeleteResponseTransfer
     */
    public function delete(
        CompanyUserInvitationDeleteRequestTransfer $companyUserInvitationDeleteRequestTransfer
    ): CompanyUserInvitationDeleteResponseTransfer;
}
