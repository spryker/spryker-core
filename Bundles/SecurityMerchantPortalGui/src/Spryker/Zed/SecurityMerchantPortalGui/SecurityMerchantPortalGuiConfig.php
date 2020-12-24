<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityMerchantPortalGuiConfig extends AbstractBundleConfig
{
    public const ROLE_MERCHANT_USER = 'ROLE_MERCHANT_USER';

    protected const MERCHANT_USER_DEFAULT_URL = '/dashboard-merchant-portal-gui/dashboard';
    protected const LOGIN_URL = '/security-merchant-portal-gui/login';

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
