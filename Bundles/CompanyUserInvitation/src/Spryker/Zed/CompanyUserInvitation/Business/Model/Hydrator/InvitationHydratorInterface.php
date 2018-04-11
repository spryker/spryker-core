<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;

interface InvitationHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function hydrate(CompanyUserInvitationTransfer $invitationTransfer): CompanyUserInvitationTransfer;
}
