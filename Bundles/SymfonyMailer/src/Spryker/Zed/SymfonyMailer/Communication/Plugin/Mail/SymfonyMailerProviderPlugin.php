<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer\Communication\Plugin\Mail;

use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailProviderPluginInterface;

/**
 * @method \Spryker\Zed\SymfonyMailer\Business\SymfonyMailerFacadeInterface getFacade()
 * @method \Spryker\Zed\SymfonyMailer\Communication\SymfonyMailerCommunicationFactory getFactory()
 * @method \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig getConfig()
 */
class SymfonyMailerProviderPlugin extends AbstractPlugin implements MailProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sends the email via `SymfonyMailer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function sendMail(MailTransfer $mailTransfer): void
    {
        $this->getFacade()->send($mailTransfer);
    }
}
