<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Mail;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface MailConstants
{
    /**
     * @var string
     */
    public const MAILCATCHER_GUI = 'MAILCATCHER_GUI';

    /**
     * @deprecated Use {@link \Spryker\Shared\SymfonyMailer\SymfonyMailerConstants::SMTP_HOST} instead.
     *
     * @var string
     */
    public const SMTP_HOST = 'MAIL_CONSTANTS:SMTP_HOST';

    /**
     * @deprecated Use {@link \Spryker\Shared\SymfonyMailer\SymfonyMailerConstants::SMTP_PORT} instead.
     *
     * @var string
     */
    public const SMTP_PORT = 'MAIL_CONSTANTS:SMTP_PORT';

    /**
     * @deprecated Use {@link \Spryker\Shared\SymfonyMailer\SymfonyMailerConstants::SMTP_ENCRYPTION} instead.
     *
     * Specification:
     * - Defines encryption mode for `SMTP`.
     * - Available values are "tls", "ssl" or "".
     *
     * @api
     *
     * @var string
     */
    public const SMTP_ENCRYPTION = 'MAIL:SMTP_ENCRYPTION';

    /**
     * @deprecated Use {@link \Spryker\Shared\SymfonyMailer\SymfonyMailerConstants::SMTP_AUTH_MODE} instead.
     *
     * Specification:
     * - Defines authentication mode for `SMTP`.
     * - Available values are "plain", "login", "cram-md5", or "".
     *
     * @api
     *
     * @var string
     */
    public const SMTP_AUTH_MODE = 'MAIL:SMTP_AUTH_MODE';

    /**
     * @deprecated Use {@link \Spryker\Shared\SymfonyMailer\SymfonyMailerConstants::SMTP_USERNAME} instead.
     *
     * Specification:
     * - Defines `username` for `SMTP`.
     *
     * @api
     *
     * @var string
     */
    public const SMTP_USERNAME = 'MAIL:SMTP_USERNAME';

    /**
     * @deprecated Use {@link \Spryker\Shared\SymfonyMailer\SymfonyMailerConstants::SMTP_PASSWORD} instead.
     *
     * Specification:
     * - Defines `password` for `SMTP`.
     *
     * @api
     *
     * @var string
     */
    public const SMTP_PASSWORD = 'MAIL:SMTP_PASSWORD';

    /**
     * Specification:
     * - Defines `sender name`.
     *
     * @api
     *
     * @var string
     */
    public const SENDER_NAME = 'MAIL:SENDER_NAME';

    /**
     * Specification:
     * - Defines `sender email`.
     *
     * @api
     *
     * @var string
     */
    public const SENDER_EMAIL = 'MAIL:SENDER_EMAIL';
}
