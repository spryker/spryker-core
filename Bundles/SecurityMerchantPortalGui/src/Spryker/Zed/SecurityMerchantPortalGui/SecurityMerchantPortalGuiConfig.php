<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const ROLE_MERCHANT_USER = 'ROLE_MERCHANT_USER';

    /**
     * @var string
     */
    protected const MERCHANT_USER_DEFAULT_URL = '/dashboard-merchant-portal-gui/dashboard';

    /**
     * @var string
     */
    protected const LOGIN_URL = '/security-merchant-portal-gui/login';

    /**
     * @var string
     */
    protected const MERCHANT_PORTAL_SECURITY_BLOCKER_ENTITY_TYPE = 'customer';

    /**
     * @var bool
     */
    protected const MERCHANT_PORTAL_SECURITY_BLOCKER_ENABLED = false;

    /**
     * Specification:
     * - Checks if the security blocker is enabled.
     * - It is disabled by default.
     *
     * @api
     *
     * @return bool
     */
    public function isMerchantPortalSecurityBlockerEnabled(): bool
    {
        return static::MERCHANT_PORTAL_SECURITY_BLOCKER_ENABLED;
    }

    /**
     * Specification:
     * - Returns the entity identifier that is used to block the password resets.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantPortalSecurityBlockerEntityType(): string
    {
        return static::MERCHANT_PORTAL_SECURITY_BLOCKER_ENTITY_TYPE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultTargetPath(): string
    {
        return static::MERCHANT_USER_DEFAULT_URL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUrlLogin(): string
    {
        return static::LOGIN_URL;
    }
}
