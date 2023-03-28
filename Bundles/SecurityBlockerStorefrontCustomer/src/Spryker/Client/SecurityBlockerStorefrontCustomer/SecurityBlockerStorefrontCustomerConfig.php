<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontCustomer;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SecurityBlockerStorefrontCustomer\SecurityBlockerStorefrontCustomerConstants;

class SecurityBlockerStorefrontCustomerConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE = 'customer';

    /**
     * Specification:
     * - Returns security blocker customer entity type.
     *
     * @api
     *
     * @return string
     */
    public function getSecurityBlockerCustomerEntityType(): string
    {
        return static::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE;
    }

    /**
     * Specification:
     * - Returns Customer time for block in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getCustomerBlockForSeconds(): int
    {
        return $this->get(SecurityBlockerStorefrontCustomerConstants::CUSTOMER_BLOCK_FOR_SECONDS, 0);
    }

    /**
     * Specification:
     * - Returns Customer blocking TTL in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getCustomerBlockingTTL(): int
    {
        return $this->get(SecurityBlockerStorefrontCustomerConstants::CUSTOMER_BLOCKING_TTL, 0);
    }

    /**
     * Specification:
     * - Returns Customer number of attempts configuration.
     *
     * @api
     *
     * @return int
     */
    public function getCustomerBlockingNumberOfAttempts(): int
    {
        return $this->get(SecurityBlockerStorefrontCustomerConstants::CUSTOMER_BLOCKING_NUMBER_OF_ATTEMPTS, 0);
    }
}
