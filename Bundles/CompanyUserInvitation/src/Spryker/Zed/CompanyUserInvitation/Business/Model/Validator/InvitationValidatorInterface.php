<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business\Model\Validator;

use Generated\Shared\Transfer\CompanyUserInvitationTransfer;

interface InvitationValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserInvitationTransfer $invitationTransfer
     *
     * @return bool
     */
    public function isValidInvitation(CompanyUserInvitationTransfer $invitationTransfer): bool;

    /**
     * @return string
     */
    public function getLastErrorMessage(): string;
}
