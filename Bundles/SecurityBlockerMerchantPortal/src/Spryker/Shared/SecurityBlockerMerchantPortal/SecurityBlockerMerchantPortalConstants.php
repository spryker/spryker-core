<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecurityBlockerMerchantPortal;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SecurityBlockerMerchantPortalConstants
{
    /**
     * Specification:
     * - Specifies the TTL period for which a merchant portal user is blocked if the number of attempts is exceeded.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PORTAL_USER_BLOCK_FOR_SECONDS = 'SECURITY_BLOCKER_MERCHANT_PORTAL:MERCHANT_PORTAL_USER_BLOCK_FOR_SECONDS';

    /**
     * Specification:
     * - Specifies the TTL period in seconds when the number of unsuccessful tries to log in will be counted for a merchant portal user.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PORTAL_USER_BLOCKING_TTL = 'SECURITY_BLOCKER_MERCHANT_PORTAL:MERCHANT_PORTAL_USER_BLOCKING_TTL';

    /**
     * Specification:
     * - Specifies the number of failed login attempts a merchant portal user can make during the `SECURITY_BLOCKER_MERCHANT_PORTAL:BLOCKING_TTL` time before it is blocked.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_PORTAL_USER_BLOCKING_NUMBER_OF_ATTEMPTS = 'SECURITY_BLOCKER_MERCHANT_PORTAL:MERCHANT_PORTAL_USER_BLOCKING_NUMBER_OF_ATTEMPTS';
}
