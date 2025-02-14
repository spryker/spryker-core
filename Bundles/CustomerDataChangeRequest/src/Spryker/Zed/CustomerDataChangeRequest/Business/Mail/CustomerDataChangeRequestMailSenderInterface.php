<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Business\Mail;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerDataChangeRequestMailSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string $verificationLink
     *
     * @return void
     */
    public function sendEmailChangeVerificationToken(CustomerTransfer $customerTransfer, string $verificationLink): void;
}
