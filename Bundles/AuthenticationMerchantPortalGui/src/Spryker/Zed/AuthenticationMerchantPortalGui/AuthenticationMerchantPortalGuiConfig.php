<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthenticationMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AuthenticationMerchantPortalGuiConfig extends AbstractBundleConfig
{
    protected const MERCHANT_USER_DEFAULT_URL_REDIRECT = '/dashboard-merchant-portal-gui/dashboard';
    protected const MERCHANT_USER_LOGOUT_REDIRECT_URL = '/authentication-merchant-portal-gui/login';

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultTargetPath(): string
    {
        return static::MERCHANT_USER_DEFAULT_URL_REDIRECT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getLogoutRedirectUrl(): string
    {
        return static::MERCHANT_USER_LOGOUT_REDIRECT_URL;
    }
}
