<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector\Business\Mail;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Auth\Communication\Controller\PasswordController;
use Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig;
use Spryker\Zed\AuthMailConnector\Communication\Plugin\Mail\RestorePasswordMailTypePlugin;

class MailTransferGenerator implements MailTransferGeneratorInterface
{
    /**
     * @var \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig
     */
    protected $config;

    protected const AUTH_PASSWORD_RESET_URL = '/auth/password/reset';

    /**
     * @param \Spryker\Zed\AuthMailConnector\AuthMailConnectorConfig $config
     */
    public function __construct(AuthMailConnectorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $email
     * @param string $token
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function generateResetPasswordMailTransfer(string $email, string $token): MailTransfer
    {
        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(RestorePasswordMailTypePlugin::MAIL_TYPE);
        $mailTransfer->addRecipient($this->generateMailRecipientTransfer($email));
        $mailTransfer->setResetPasswordLink($this->generateResetPasswordLink($token));

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
     * @return string
     */
    protected function generateResetPasswordLink(string $token): string
    {
        $baseUrlZed = $this->config->getBaseUrlZed();
        $query = $this->generateResetPasswordLinkQuery($token);

        return sprintf('%s%s?%s', $baseUrlZed, static::AUTH_PASSWORD_RESET_URL, $query);
    }

    /**
     * @param string $token
     *
     * @return string
     */
    protected function generateResetPasswordLinkQuery(string $token): string
    {
        return http_build_query([
            PasswordController::PARAM_TOKEN => $token,
        ]);
    }
}
