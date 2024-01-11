<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class UserMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var bool
     */
    protected const IS_EMAIL_UPDATE_PASSWORD_VERIFICATION_ENABLED = false;

    /**
     * @var bool
     */
    protected const IS_SECURITY_BLOCKER_FOR_MERCHANT_USER_EMAIL_CHANGING_ENABLED = false;

    /**
     * @uses \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConfig::MERCHANT_PORTAL_USER_ENTITY_TYPE
     *
     * @var string
     */
    protected const ENTITY_TYPE_MERCHANT_PORTAL_USER = 'merchant-portal-user';

    /**
     * Specification:
     * - Returns whether email update should be protected with password validation.
     *
     * @api
     *
     * @return bool
     */
    public function isEmailUpdatePasswordVerificationEnabled(): bool
    {
        return static::IS_EMAIL_UPDATE_PASSWORD_VERIFICATION_ENABLED;
    }

    /**
     * Specification:
     * - Defines whether merchant user email change is protected by security blocker functionality.
     *
     * @api
     *
     * @return bool
     */
    public function isSecurityBlockerForMerchantUserEmailChangingEnabled(): bool
    {
        return static::IS_SECURITY_BLOCKER_FOR_MERCHANT_USER_EMAIL_CHANGING_ENABLED;
    }

    /**
     * Specification:
     * - Returns merchant portal user entity type for security blocker functionality.
     *
     * @api
     *
     * @return string
     */
    public function getSecurityBlockerMerchantPortalUserEntityType(): string
    {
        return static::ENTITY_TYPE_MERCHANT_PORTAL_USER;
    }
}
