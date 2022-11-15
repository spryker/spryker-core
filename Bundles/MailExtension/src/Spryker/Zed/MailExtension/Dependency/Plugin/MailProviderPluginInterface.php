<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MailExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MailTransfer;

/**
 * Use this plugin to add a new provider for mail sending.
 */
interface MailProviderPluginInterface
{
    /**
     * Specification:
     * - Receives the fully configured `MailTransfer`.
     * - Sends the mail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function sendMail(MailTransfer $mailTransfer);
}
