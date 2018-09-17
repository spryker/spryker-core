<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AuthMailConnector\Communication\AuthMailConnectorCommunicationFactory getFactory()
 */
class AuthPasswordResetMailSenderPlugin extends AbstractPlugin implements AuthPasswordResetSenderInterface
{
    const SUBJECT = 'Password reset request';
    const TEMPLATE = 'Auth.password.reset';

    /**
     * @api
     *
     * @param string $email
     * @param string $token
     *
     * @return void
     */
    public function send($email, $token)
    {
        $mailTransfer = new MailTransfer();
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);

        $mailTransfer->addRecipient($mailRecipientTransfer);
        $mailTransfer->setSubject(static::SUBJECT);
        $mailTransfer->setTemplateName(static::TEMPLATE);
        $mailTransfer->setMerge(true);
        $mailTransfer->setMergeLanguage('handlebars');
        $mailTransfer->setGlobalMergeVars([
            'reset_password_token' => $token,
        ]);

        $this->getFactory()
            ->getMailFacade()
            ->sendMail($mailTransfer);
    }
}
