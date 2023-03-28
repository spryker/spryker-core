<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityBlockerMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityBlockerMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Client\SecurityBlockerMerchantPortal\SecurityBlockerMerchantPortalConfig::MERCHANT_PORTAL_USER_ENTITY_TYPE
     *
     * @var string
     */
    protected const SECURITY_BLOCKER_MERCHANT_PORTAL_USER_ENTITY_TYPE = 'merchant-portal-user';

    /**
     * @see \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\MerchantUserSecurityPlugin::addFirewall().
     *
     * @var string
     */
    protected const MERCHANT_PORTAL_LOGIN_CHECK_URL = 'security-merchant-portal-gui_login_check';

    /**
     * Specification:
     * - Returns security blocker merchant portal user entity type.
     *
     * @api
     *
     * @return string
     */
    public function getSecurityBlockerMerchantPortalUserEntityType(): string
    {
        return static::SECURITY_BLOCKER_MERCHANT_PORTAL_USER_ENTITY_TYPE;
    }

    /**
     * Specification:
     * - Returns login check URL for merchant portal user.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantPortalUserLoginCheckUrl(): string
    {
        return static::MERCHANT_PORTAL_LOGIN_CHECK_URL;
    }
}
