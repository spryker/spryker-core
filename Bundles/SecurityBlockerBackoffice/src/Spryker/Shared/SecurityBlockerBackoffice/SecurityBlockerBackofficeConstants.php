<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecurityBlockerBackoffice;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SecurityBlockerBackofficeConstants
{
    /**
     * Specification:
     * - Specifies the TTL configuration, the period for which the backoffice user is blocked if the number of attempts is exceeded for backoffice.
     *
     * @api
     *
     * @var string
     */
    public const BACKOFFICE_USER_BLOCK_FOR_SECONDS = 'SECURITY_BLOCKER_BACKOFFICE:BACKOFFICE_USER_BLOCK_FOR_SECONDS';

    /**
     * Specification:
     * - Specifies the TTL configuration, the period when number of unsuccessful tries will be counted for backoffice user.
     *
     * @api
     *
     * @var string
     */
    public const BACKOFFICE_USER_BLOCKING_TTL = 'SECURITY_BLOCKER_BACKOFFICE:BACKOFFICE_USER_BLOCKING_TTL';

    /**
     * Specification:
     * - Specifies number of failed login attempts a backoffice user can make during the `SECURITY_BLOCKER_BACKOFFICE:BLOCKING_TTL` time before it is blocked.
     *
     * @api
     *
     * @var string
     */
    public const BACKOFFICE_USER_BLOCKING_NUMBER_OF_ATTEMPTS = 'SECURITY_BLOCKER_BACKOFFICE:BACKOFFICE_USER_BLOCKING_NUMBER_OF_ATTEMPTS';
}
