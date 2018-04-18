<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Writer;

use Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCreateResponseTransfer;

interface InvitationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer $companyUserInvitationCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationCreateResponseTransfer
     */
    public function create(
        CompanyUserInvitationCreateRequestTransfer $companyUserInvitationCreateRequestTransfer
    ): CompanyUserInvitationCreateResponseTransfer;
}
