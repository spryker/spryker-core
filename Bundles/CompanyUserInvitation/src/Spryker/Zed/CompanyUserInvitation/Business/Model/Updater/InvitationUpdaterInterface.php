<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Updater;

use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;

interface InvitationUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer
     * @param string $status
     *
     * @return mixed
     */
    public function updateStatus(
        CompanyUserInvitationCollectionTransfer $companyUserInvitationCollectionTransfer,
        string $status
    ): void;
}
