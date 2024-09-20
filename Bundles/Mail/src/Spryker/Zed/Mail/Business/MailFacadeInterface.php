<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business;

use Generated\Shared\Transfer\MailTransfer;

interface MailFacadeInterface
{
    /**
     * Specification:
     * - Prepares the mail before send
     * - Builds the needed MailTransfer by given MailType specification
     * - Executes {@link Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function handleMail(MailTransfer $mailTransfer);

    /**
     * Specification:
     * - Sends the mail.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SymfonyMailer\Business\SymfonyMailerFacadeInterface::send()} instead.
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function sendMail(MailTransfer $mailTransfer);
}
