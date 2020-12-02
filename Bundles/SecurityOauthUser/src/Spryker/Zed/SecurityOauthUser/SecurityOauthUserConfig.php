<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityOauthUserConfig extends AbstractBundleConfig
{
    public const REQUEST_PARAMETER_AUTHENTICATION_CODE = 'code';
    public const REQUEST_PARAMETER_AUTHENTICATION_STATE = 'state';
    public const ROLE_BACK_OFFICE_USER = 'ROLE_BACK_OFFICE_USER';
    public const ROLE_OAUTH_USER = 'ROLE_OAUTH_USER';

    protected const BACK_OFFICE_ROUTE_PATTERN = '^/';
    protected const IGNORABLE_ROUTE_PATTERN = '^/security-oauth-user';
    protected const HOME_PATH = '/';

    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::LOGIN_PATH
     */
    protected const LOGIN_PATH = '/security-gui/login';
    protected const LOGOUT_PATH = '/auth/logout';

    /**
     * @api
     *
     * @return string[]
     */
    public function getOauthUserRoles(): array
    {
        return [static::ROLE_BACK_OFFICE_USER, static::ROLE_OAUTH_USER];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBackOfficeRoutePattern(): string
    {
        return static::BACK_OFFICE_ROUTE_PATTERN;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getIgnorablePaths(): string
    {
        return static::IGNORABLE_ROUTE_PATTERN;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUrlHome(): string
    {
        return static::HOME_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUrlLogin(): string
    {
        return static::LOGIN_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getUrlLogout():string
    {
        return static::LOGIN_PATH;
    }
}
