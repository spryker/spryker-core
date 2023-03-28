<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerMerchantPortal;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConstants;

class SecurityBlockerMerchantPortalConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const MERCHANT_PORTAL_USER_ENTITY_TYPE = 'merchant-portal-user';

    /**
     * Specification:
     * - Returns security blocker merchant portal user entity type.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantPortalUserSecurityBlockerEntityType(): string
    {
        return static::MERCHANT_PORTAL_USER_ENTITY_TYPE;
    }

    /**
     * Specification:
     * - Returns merchant portal user time for block in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getMerchantPortalUserBlockingTTL(): int
    {
        return $this->get(SecurityBlockerMerchantPortalConstants::MERCHANT_PORTAL_USER_BLOCKING_TTL, 0);
    }

    /**
     * Specification:
     * - Returns merchant portal user blocking TTL in seconds.
     *
     * @api
     *
     * @return int
     */
    public function getMerchantPortalUserBlockForSeconds(): int
    {
        return $this->get(SecurityBlockerMerchantPortalConstants::MERCHANT_PORTAL_USER_BLOCK_FOR_SECONDS, 0);
    }

    /**
     * Specification:
     * - Returns merchant portal user number of attempts configuration.
     *
     * @api
     *
     * @return int
     */
    public function getMerchantPortalUserBlockingNumberOfAttempts(): int
    {
        return $this->get(SecurityBlockerMerchantPortalConstants::MERCHANT_PORTAL_USER_BLOCKING_NUMBER_OF_ATTEMPTS, 0);
    }
}
