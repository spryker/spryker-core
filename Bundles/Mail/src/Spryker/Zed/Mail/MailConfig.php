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
     * @api
     *
     * @return string
     */
    public function getSenderName()
    {
        return $this->get(
            MailConstants::SENDER_NAME,
            ''
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
            ''
        );
    }

    /**
     * @api
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
     * @return int
     */
    public function getSmtpPort(): int
    {
        return $this->get(MailConstants::SMTP_PORT, 25);
    }

    /**
     * @api
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
     * @return string
     */
    public function getSmtpAuthMode(): string
    {
        return $this->get(MailConstants::SMTP_AUTH_MODE, '');
    }

    /**
     * @api
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
     * @return string
     */
    public function getSmtpPassword(): string
    {
        return $this->get(MailConstants::SMTP_PASSWORD, '');
    }
}
