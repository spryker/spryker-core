<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityOauthUserConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     *  - If the user does not exist it will be created automatically based on data from an external service.
     */
    public const AUTHENTICATION_STRATEGY_CREATE_USER_ON_FIRST_LOGIN = 'CREATE_USER_ON_FIRST_LOGIN_AUTHENTICATION_STRATEGY';

    /**
     * Specification:
     *  - Accepts only existing users for authentication.
     */
    public const AUTHENTICATION_STRATEGY_ACCEPT_ONLY_EXISTING_USERS = 'ACCEPT_ONLY_EXISTING_USER_AUTHENTICATION_STRATEGY';

    public const REQUEST_PARAMETER_AUTHENTICATION_CODE = 'code';
    public const REQUEST_PARAMETER_AUTHENTICATION_STATE = 'state';
    public const ROLE_BACK_OFFICE_USER = 'ROLE_BACK_OFFICE_USER';
    public const ROLE_OAUTH_USER = 'ROLE_OAUTH_USER';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     */
    public const OAUTH_USER_STATUS_ACTIVE = 'active';

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
    public function getUrlLogout(): string
    {
        return static::LOGIN_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getOauthUserCreationStatus(): string
    {
        return static::OAUTH_USER_STATUS_ACTIVE;
    }

    /**
     * Specification:
     *  - Defines by which strategy Oauth user authentication should be.
     *
     * @api
     *
     * @return string
     */
    public function getAuthenticationStrategy(): string
    {
        return static::AUTHENTICATION_STRATEGY_CREATE_USER_ON_FIRST_LOGIN;
    }
}
