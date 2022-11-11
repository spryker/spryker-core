<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail;

use Spryker\Shared\Mail\MailConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class MailConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const MAIL_TYPE_ALL = '*';

    /**
     * @api
     *
     * @return string
     */
    public function getSenderName()
    {
        return $this->get(
            MailConstants::SENDER_NAME,
            '',
        );
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSenderEmail()
    {
        return $this->get(
            MailConstants::SENDER_EMAIL,
            '',
        );
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig::getSmtpHost()} instead.
     *
     * @return string
     */
    public function getSmtpHost(): string
    {
        return $this->get(MailConstants::SMTP_HOST, 'localhost');
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig::getSmtpPort()} instead.
     *
     * @return int
     */
    public function getSmtpPort(): int
    {
        return $this->get(MailConstants::SMTP_PORT, 25);
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig::isSmtpEncrypted()} instead.
     *
     * @return string
     */
    public function getSmtpEncryption(): string
    {
        return $this->get(MailConstants::SMTP_ENCRYPTION, '');
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig::getSmtpAuthMode()} instead.
     *
     * @return string
     */
    public function getSmtpAuthMode(): string
    {
        return $this->get(MailConstants::SMTP_AUTH_MODE, '');
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig::getSmtpUsername()} instead.
     *
     * @return string
     */
    public function getSmtpUsername(): string
    {
        return $this->get(MailConstants::SMTP_USERNAME, '');
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SymfonyMailer\SymfonyMailerConfig::getSmtpPassword()} instead.
     *
     * @return string
     */
    public function getSmtpPassword(): string
    {
        return $this->get(MailConstants::SMTP_PASSWORD, '');
    }
}
