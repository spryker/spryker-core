<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Deleter;

use Generated\Shared\Transfer\CompanyUserInvitationAffectedReportTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;

interface InvitationDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationAffectedReportTransfer
     */
    public function deleteInvitations(
        CompanyUserInvitationCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserInvitationAffectedReportTransfer;
}
