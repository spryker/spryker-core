<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Business\Mail;

use Generated\Shared\Transfer\MailTransfer;

interface MailTransferGeneratorInterface
{
    /**
     * @param string $email
     * @param string $token
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function generateResetPasswordMailTransfer(string $email, string $token): MailTransfer;
}
