<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer;

use Spryker\Shared\SymfonyMailer\SymfonyMailerConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SymfonyMailerConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the host that mail sending serves.
     *
     * @api
     *
     * @return string
     */
    public function getSmtpHost(): string
    {
        return $this->get(SymfonyMailerConstants::SMTP_HOST, 'localhost');
    }

    /**
     * Specification:
     * - Returns the port that mail sending serves.
     *
     * @api
     *
     * @return int
     */
    public function getSmtpPort(): int
    {
        return $this->get(SymfonyMailerConstants::SMTP_PORT, 25);
    }

    /**
     * Specification:
     * - Returns whether the SMTP encryption for mail sending is enabled or not.
     *
     * @api
     *
     * @return bool
     */
    public function isSmtpEncrypted(): bool
    {
        return $this->get(SymfonyMailerConstants::SMTP_ENCRYPTION, false);
    }

    /**
     * Specification:
     * - Returns the SMTP auth mode for mail sending.
     *
     * @api
     *
     * @return string
     */
    public function getSmtpAuthMode(): string
    {
        return $this->get(SymfonyMailerConstants::SMTP_AUTH_MODE, '');
    }

    /**
     * Specification:
     * - Returns the SMTP username for mail sending.
     *
     * @api
     *
     * @return string
     */
    public function getSmtpUsername(): string
    {
        return $this->get(SymfonyMailerConstants::SMTP_USERNAME, '');
    }

    /**
     * Specification:
     * - Returns the SMTP password for mail sending.
     *
     * @api
     *
     * @return string
     */
    public function getSmtpPassword(): string
    {
        return $this->get(SymfonyMailerConstants::SMTP_PASSWORD, '');
    }
}
