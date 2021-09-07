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
     *
     * @var string
     */
    public const AUTHENTICATION_STRATEGY_CREATE_USER_ON_FIRST_LOGIN = 'AUTHENTICATION_STRATEGY_CREATE_USER_ON_FIRST_LOGIN';

    /**
     * Specification:
     *  - Accepts only existing users for authentication.
     *
     * @var string
     */
    public const AUTHENTICATION_STRATEGY_ACCEPT_ONLY_EXISTING_USERS = 'AUTHENTICATION_STRATEGY_ACCEPT_ONLY_EXISTING_USERS';

    /**
     * @var string
     */
    public const REQUEST_PARAMETER_AUTHENTICATION_CODE = 'code';
    /**
     * @var string
     */
    public const REQUEST_PARAMETER_AUTHENTICATION_STATE = 'state';

    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::ROLE_BACK_OFFICE_USER
     * @var string
     */
    public const ROLE_BACK_OFFICE_USER = 'ROLE_BACK_OFFICE_USER';
    /**
     * @var string
     */
    public const ROLE_OAUTH_USER = 'ROLE_OAUTH_USER';
    /**
     * @var string
     */
    public const ROUTE_NAME_OAUTH_USER_LOGIN = 'security-oauth-user:login';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     * @var string
     */
    protected const OAUTH_USER_STATUS_ACTIVE = 'active';

    /**
     * @var string
     */
    protected const BACK_OFFICE_ROUTE_PATTERN = '^/';
    /**
     * @var string
     */
    protected const IGNORABLE_ROUTE_PATTERN = '^/security-oauth-user|^/security-gui';

    /**
     * @var string
     */
    protected const HOME_PATH = '/';

    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::LOGIN_PATH
     * @var string
     */
    protected const LOGIN_PATH = '/security-gui/login';

    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Plugin\Security\UserSecurityPlugin::PATH_LOGOUT
     * @var string
     */
    protected const LOGOUT_PATH = '/auth/logout';

    /**
     * @uses \Spryker\Shared\Acl\AclConstants::ROOT_GROUP
     * @var string
     */
    protected const OAUTH_USER_GROUP_NAME = 'root_group';

    /**
     * Specification:
     *  - Defines the Oauth user roles for the symfony authentication.
     *
     * @api
     *
     * @return string[]
     */
    public function getOauthUserRoles(): array
    {
        return [static::ROLE_BACK_OFFICE_USER, static::ROLE_OAUTH_USER];
    }

    /**
     * Specification:
     *  - Defines secured back-office route pattern.
     *
     * @api
     *
     * @return string
     */
    public function getBackOfficeRoutePattern(): string
    {
        return static::BACK_OFFICE_ROUTE_PATTERN;
    }

    /**
     * Specification:
     * - Defines the ignorable path in order to open an entry point for the external system.
     *
     * @api
     *
     * @return string
     */
    public function getIgnorablePaths(): string
    {
        return static::IGNORABLE_ROUTE_PATTERN;
    }

    /**
     * Specification:
     * - Defines the URL where the user will be redirected after successful authentication.
     *
     * @api
     *
     * @return string
     */
    public function getUrlHome(): string
    {
        return static::HOME_PATH;
    }

    /**
     * Specification:
     * - Defines the URL of the login page.
     * - Also it is used for the user redirect on authentication failure.
     *
     * @api
     *
     * @return string
     */
    public function getUrlLogin(): string
    {
        return static::LOGIN_PATH;
    }

    /**
     * Specification:
     * - Defines the logout URL.
     *
     * @api
     *
     * @return string
     */
    public function getUrlLogout(): string
    {
        return static::LOGOUT_PATH;
    }

    /**
     * Specification:
     * - Defines the Oauth user status when the `create user on first login` strategy is selected.
     *
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
     * - Defines which Oauth user status considered as active.
     *
     * @api
     *
     * @return string
     */
    public function getOauthUserStatusActive(): string
    {
        return static::OAUTH_USER_STATUS_ACTIVE;
    }

    /**
     * Specification:
     * - Defines the Oauth user group name when the `create user on first login` strategy is selected.
     *
     * @api
     *
     * @return string
     */
    public function getOauthUserGroupName(): string
    {
        return static::OAUTH_USER_GROUP_NAME;
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
