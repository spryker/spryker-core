<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecurityBlockerStorefrontCustomer;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class SecurityBlockerStorefrontCustomerConstants
{
    /**
     * Specification:
     * - Specifies the TTL configuration, the period for which the agent is blocked if the number of attempts is exceeded for agent.
     *
     * @api
     *
     * @var string
     */
    public const CUSTOMER_BLOCK_FOR_SECONDS = 'SECURITY_BLOCKER_STOREFRONT_CUSTOMER:CUSTOMER_BLOCK_FOR_SECONDS';

    /**
     * Specification:
     * - Specifies the TTL configuration, the period when number of unsuccessful tries will be counted for customer.
     *
     * @api
     *
     * @var string
     */
    public const CUSTOMER_BLOCKING_TTL = 'SECURITY_BLOCKER_STOREFRONT_CUSTOMER:CUSTOMER_BLOCKING_TTL';

    /**
     * Specification:
     * - Specifies number of failed login attempt a customer can make during the `SECURITY_BLOCKER_STOREFRONT:CUSTOMER_BLOCKING_TTL` time before it is blocked.
     *
     * @api
     *
     * @var string
     */
    public const CUSTOMER_BLOCKING_NUMBER_OF_ATTEMPTS = 'SECURITY_BLOCKER_STOREFRONT_CUSTOMER:CUSTOMER_BLOCKING_NUMBER_OF_ATTEMPTS';
}
