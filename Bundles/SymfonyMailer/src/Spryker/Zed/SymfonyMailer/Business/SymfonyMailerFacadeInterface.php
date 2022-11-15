<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer\Business;

use Generated\Shared\Transfer\MailTransfer;

interface SymfonyMailerFacadeInterface
{
    /**
     * Specification:
     * - Sends the mail.
     * - Requires `RecipientTransfer.email` transfer property to be set.
     * - Requires `MailTransfer.subject` transfer property to be set.
     * - Requires `MailTransfer.sender` transfer property to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function send(MailTransfer $mailTransfer): void;
}
