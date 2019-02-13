<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailLinkTransfer;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\AuthMailConnector\Communication\Plugin\Mail\RestorePasswordMailTypePlugin;
use Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AuthMailConnector\Communication\AuthMailConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig getConfig()
 */
class AuthPasswordResetMailSenderPlugin extends AbstractPlugin implements AuthPasswordResetSenderInterface
{
    protected const PARAM_TOKEN = 'token';
    protected const AUTH_PASSWORD_RESET_URL = '/auth/password/reset';
    protected const HREF_FORMAT = '%s%s?%s';

    protected const RESTORE_PASSWORD_LINK_TEXT = 'mail.auth.restore_password.text';
    protected const RESTORE_PASSWORD_LINK_LABEL = 'mail.auth.restore_password.label';

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
        $mailTransfer = $this->generateMailTransfer($email, $token);

        $this->getFactory()->getMailFacade()->handleMail($mailTransfer);
    }

    /**
     * @param string $email
     * @param string $token
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function generateMailTransfer(string $email, string $token): MailTransfer
    {
        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(RestorePasswordMailTypePlugin::MAIL_TYPE);

        $mailRecipientTransfer = $this->generateMailRecipientTransfer($email);
        $mailTransfer->addRecipient($mailRecipientTransfer);

        $mailLinkTransfer = $this->generateMailLinkTransfer($token);
        $mailTransfer->addLink($mailLinkTransfer);

        return $mailTransfer;
    }

    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\MailRecipientTransfer
     */
    protected function generateMailRecipientTransfer(string $email): MailRecipientTransfer
    {
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);

        return $mailRecipientTransfer;
    }

    /**
     * @param string $token
     *
     * @return \Generated\Shared\Transfer\MailLinkTransfer
     */
    protected function generateMailLinkTransfer(string $token): MailLinkTransfer
    {
        $mailLinkTransfer = new MailLinkTransfer();
        $mailLinkTransfer
            ->setText(static::RESTORE_PASSWORD_LINK_TEXT)
            ->setLabel(static::RESTORE_PASSWORD_LINK_LABEL)
            ->setHref($this->generateHref($token));

        return $mailLinkTransfer;
    }

    /**
     * @param string $token
     *
     * @return string
     */
    protected function generateHref(string $token): string
    {
        $baseUrlZed = $this->getConfig()->getBaseUrlZed();

        $query = http_build_query([
            static::PARAM_TOKEN => $token,
        ]);

        return sprintf(static::HREF_FORMAT, $baseUrlZed, static::AUTH_PASSWORD_RESET_URL, $query);
    }
}
