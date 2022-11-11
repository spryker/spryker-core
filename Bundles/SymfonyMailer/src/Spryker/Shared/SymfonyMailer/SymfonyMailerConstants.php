<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SymfonyMailer;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SymfonyMailerConstants
{
    /**
     * Specification:
     * - Defines `host` for SMTP.
     *
     * @api
     *
     * @var string
     */
    public const SMTP_HOST = 'SYMFONY_MAILER:SMTP_HOST';

    /**
     * Specification:
     * - Defines `port` for SMTP.
     *
     * @api
     *
     * @var string
     */
    public const SMTP_PORT = 'SYMFONY_MAILER:SMTP_PORT';

    /**
     * Specification:
     * - Defines whether encryption mode for `SMTP` is enabled or not.
     *
     * @api
     *
     * @var string
     */
    public const SMTP_ENCRYPTION = 'SYMFONY_MAILER:SMTP_ENCRYPTION';

    /**
     * Specification:
     * - Defines authentication mode for `SMTP`.
     * - Available values are "plain", "login", "cram-md5", or "".
     *
     * @api
     *
     * @var string
     */
    public const SMTP_AUTH_MODE = 'SYMFONY_MAILER:SMTP_AUTH_MODE';

    /**
     * Specification:
     * - Defines `username` for `SMTP`.
     *
     * @api
     *
     * @var string
     */
    public const SMTP_USERNAME = 'SYMFONY_MAILER:SMTP_USERNAME';

    /**
     * Specification:
     * - Defines `password` for `SMTP`.
     *
     * @api
     *
     * @var string
     */
    public const SMTP_PASSWORD = 'SYMFONY_MAILER:SMTP_PASSWORD';
}
