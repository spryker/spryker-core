<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Writer;

use Generated\Shared\Transfer\CompanyUserInvitationCreateResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationDeleteResultTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;

interface InvitationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCreateResultTransfer
     */
    public function create(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationCreateResultTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $companyUserInvitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationDeleteResultTransfer
     */
    public function delete(
        CompanyUserInvitationTransfer $companyUserInvitationTransfer
    ): CompanyUserInvitationDeleteResultTransfer;
}
