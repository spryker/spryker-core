<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Model\Provider;

use Generated\Shared\Transfer\MailTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
interface SwiftMailerInterface
{
    /**
     * Specification:
     * - Receives the fully configured `MailTransfer`.
     * - Sends the mail.
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function sendMail(MailTransfer $mailTransfer);
}
