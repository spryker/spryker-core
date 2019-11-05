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
    public const MAIL_TYPE_ALL = '*';

    /**
     * @return string
     */
    public function getSenderName()
    {
        return 'mail.sender.name';
    }

    /**
     * @return string
     */
    public function getSenderEmail()
    {
        return 'mail.sender.email';
    }

    /**
     * @return string
     */
    public function getSmtpHost(): string
    {
        return $this->get(MailConstants::SMTP_HOST, 'localhost');
    }

    /**
     * @return int
     */
    public function getSmtpPort(): int
    {
        return $this->get(MailConstants::SMTP_PORT, 25);
    }

    /**
     * @return string
     */
    public function getSmtpEncryption(): string
    {
        return $this->get(MailConstants::SMTP_ENCRYPTION, '');
    }

    /**
     * @return string
     */
    public function getSmtpAuthMode(): string
    {
        return $this->get(MailConstants::SMTP_AUTH_MODE, '');
    }

    /**
     * @return string
     */
    public function getSmtpUsername(): string
    {
        return $this->get(MailConstants::SMTP_USERNAME, '');
    }

    /**
     * @return string
     */
    public function getSmtpPassword(): string
    {
        return $this->get(MailConstants::SMTP_PASSWORD, '');
    }
}
