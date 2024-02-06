<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AgentSecurityBlockerMerchantPortal;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AgentSecurityBlockerMerchantPortalConstants
{
    /**
     * Specification:
     * - Specifies the TTL period for which an agent merchant portal is blocked if the number of attempts is exceeded.
     *
     * @api
     *
     * @var string
     */
    public const AGENT_MERCHANT_PORTAL_BLOCK_FOR_SECONDS = 'AGENT_SECURITY_BLOCKER_MERCHANT_PORTAL:AGENT_MERCHANT_PORTAL_BLOCK_FOR_SECONDS';

    /**
     * Specification:
     * - Specifies the TTL period in seconds when the number of unsuccessful tries to log in will be counted for an agent merchant portal.
     *
     * @api
     *
     * @var string
     */
    public const AGENT_MERCHANT_PORTAL_BLOCKING_TTL = 'AGENT_SECURITY_BLOCKER_MERCHANT_PORTAL:AGENT_MERCHANT_PORTAL_BLOCKING_TTL';

    /**
     * Specification:
     * - Specifies the number of failed login attempts an agent merchant portal can make during the `AGENT_SECURITY_BLOCKER_MERCHANT_PORTAL:AGENT_MERCHANT_PORTAL_BLOCKING_TTL` time before it is blocked.
     *
     * @api
     *
     * @var string
     */
    public const AGENT_MERCHANT_PORTAL_BLOCKING_NUMBER_OF_ATTEMPTS = 'AGENT_SECURITY_BLOCKER_MERCHANT_PORTAL:AGENT_MERCHANT_PORTAL_BLOCKING_NUMBER_OF_ATTEMPTS';
}
