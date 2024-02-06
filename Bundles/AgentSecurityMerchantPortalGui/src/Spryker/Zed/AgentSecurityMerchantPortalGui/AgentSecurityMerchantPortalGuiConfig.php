<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AgentSecurityMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const SECURITY_AGENT_MERCHANT_PORTAL_LOGIN_FORM_AUTHENTICATOR = 'security.AgentMerchantUser.login_form.authenticator';

    /**
     * @var string
     */
    protected const ROUTE_LOGIN = 'agent-security-merchant-portal-gui:login';

    /**
     * @var string
     */
    protected const PATH_LOGIN = '/agent-security-merchant-portal-gui/login';

    /**
     * @var string
     */
    protected const PATH_LOGIN_CHECK = '/agent-security-merchant-portal-gui/login_check';

    /**
     * @var string
     */
    protected const PATH_LOGOUT = '/agent-security-merchant-portal-gui/logout';

    /**
     * @var string
     */
    protected const PATH_DEFAULT_TARGET = '/agent-dashboard-merchant-portal-gui/merchant-users';

    /**
     * @var string
     */
    protected const ROUTE_PATTERN_AGENT_MERCHANT_PORTAL = '^\/agent(?!security-merchant-portal-gui\/login$).*';

    /**
     * @var string
     */
    protected const ROUTE_PATTERN_AGENT_MERCHANT_PORTAL_LOGIN = '^/agent-security-merchant-portal-gui/login$';

    /**
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'AgentMerchantUser';

    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\MerchantUserSecurityPlugin::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const MERCHANT_USER_SECURITY_FIREWALL_NAME = 'MerchantUser';

    /**
     * @see \Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser
     *
     * @var string
     */
    protected const MERCHANT_USER_CLASS_NAME = '\Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser';

    /**
     * @var string
     */
    protected const ROLE_MERCHANT_AGENT = 'ROLE_MERCHANT_AGENT';

    /**
     * @var string
     */
    protected const ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    /**
     * @var string
     */
    protected const ROLE_PREVIOUS_ADMIN = 'ROLE_PREVIOUS_ADMIN';

    /**
     * Specification:
     * - Defines security agent merchant portal login form authenticator name.
     *
     * @api
     *
     * @return string
     */
    public function getSecurityAgentMerchantPortalLoginFormAuthenticatorName(): string
    {
        return static::SECURITY_AGENT_MERCHANT_PORTAL_LOGIN_FORM_AUTHENTICATOR;
    }

    /**
     * Specification:
     * - Defines route of the login page.
     *
     * @api
     *
     * @return string
     */
    public function getRouteLogin(): string
    {
        return static::ROUTE_LOGIN;
    }

    /**
     * Specification:
     * - Defines the URL of the login page.
     *
     * @api
     *
     * @return string
     */
    public function getUrlLogin(): string
    {
        return static::PATH_LOGIN;
    }

    /**
     * Specification:
     * - Defines the URL of the login check page.
     *
     * @api
     *
     * @return string
     */
    public function getUrlLoginCheck(): string
    {
        return static::PATH_LOGIN_CHECK;
    }

    /**
     * Specification:
     * - Defines the URL of the logout page.
     *
     * @api
     *
     * @return string
     */
    public function getUrlLogout(): string
    {
        return static::PATH_LOGOUT;
    }

    /**
     * Specification:
     * - Defines the default target URL.
     *
     * @api
     *
     * @return string
     */
    public function getUrlDefaultTarget(): string
    {
        return static::PATH_DEFAULT_TARGET;
    }

    /**
     * Specification:
     * - Defines the agent merchant portal route pattern.
     *
     * @api
     *
     * @return string
     */
    public function getRoutePatternAgentMerchantPortal(): string
    {
        return static::ROUTE_PATTERN_AGENT_MERCHANT_PORTAL;
    }

    /**
     * Specification:
     * - Defines the agent merchant portal login route pattern.
     *
     * @api
     *
     * @return string
     */
    public function getRoutePatternAgentMerchantPortalLogin(): string
    {
        return static::ROUTE_PATTERN_AGENT_MERCHANT_PORTAL_LOGIN;
    }

    /**
     * Specification:
     * - Defines security firewall name.
     *
     * @api
     *
     * @return string
     */
    public function getSecurityFirewallName(): string
    {
        return static::SECURITY_FIREWALL_NAME;
    }

    /**
     * Specification:
     * - Defines merchant security firewall name.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantUserSecurityFirewallName(): string
    {
        return static::MERCHANT_USER_SECURITY_FIREWALL_NAME;
    }

    /**
     * Specification:
     * - Defines merchant user class name.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantUserClassName(): string
    {
        return static::MERCHANT_USER_CLASS_NAME;
    }

    /**
     * Specification:
     * - Defines role merchant agent.
     *
     * @api
     *
     * @return string
     */
    public function getRoleMerchantAgent(): string
    {
        return static::ROLE_MERCHANT_AGENT;
    }

    /**
     * Specification:
     * - Defines role allowed to switch.
     *
     * @api
     *
     * @return string
     */
    public function getRoleAllowedToSwitch(): string
    {
        return static::ROLE_ALLOWED_TO_SWITCH;
    }

    /**
     * Specification:
     * - Defines role previous admin.
     *
     * @api
     *
     * @return string
     */
    public function getRolePreviousAdmin(): string
    {
        return static::ROLE_PREVIOUS_ADMIN;
    }
}
