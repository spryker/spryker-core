<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Builder;

use Spryker\Zed\SecurityMerchantPortalGui\Communication\Form\MerchantLoginForm;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OptionsBuilder implements OptionsBuilderInterface
{
    /**
     * @var string
     */
    protected const MERCHANT_PORTAL_ROUTE_PATTERN = '^/(.+)-merchant-portal-gui/';

    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Expander\SecurityBuilderExpander::SECURITY_MERCHANT_PORTAL_LOGIN_FORM_AUTHENTICATOR
     *
     * @var string
     */
    protected const SECURITY_MERCHANT_PORTAL_LOGIN_FORM_AUTHENTICATOR = 'security.MerchantUser.login_form.authenticator';

    /**
     * @var string
     */
    protected const ROUTE_LOGIN = 'security-merchant-portal-gui:login';

    /**
     * @var string
     */
    protected const PATH_LOGIN_CHECK = '/security-merchant-portal-gui/login_check';

    /**
     * @var string
     */
    protected const PATH_LOGOUT = '/security-merchant-portal-gui/logout';

    /**
     * @var string
     */
    protected const APPLICATION_MERCHANT_PORTAL = 'MERCHANT_PORTAL';

    /**
     * @see \Symfony\Component\Form\Extension\Csrf\CsrfExtension::loadTypeExtensions()
     *
     * @var string
     */
    protected const FORM_FIELD_CSRF_TOKEN = '_token';

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected UserProviderInterface $userProvider;

    /**
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     */
    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @return array<mixed>
     */
    public function buildOptions(): array
    {
        return [
            'pattern' => $this->getMerchantPortalRoutePattern(),
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => static::PATH_LOGIN_CHECK,
                'username_parameter' => MerchantLoginForm::FORM_NAME . '[' . MerchantLoginForm::FIELD_USERNAME . ']',
                'password_parameter' => MerchantLoginForm::FORM_NAME . '[' . MerchantLoginForm::FIELD_PASSWORD . ']',
                'csrf_parameter' => MerchantLoginForm::FORM_NAME . '[' . static::FORM_FIELD_CSRF_TOKEN . ']',
                'csrf_token_id' => MerchantLoginForm::FORM_NAME,
                'with_csrf' => true,
                'authenticators' => [
                    static::SECURITY_MERCHANT_PORTAL_LOGIN_FORM_AUTHENTICATOR,
                ],
            ],
            'logout' => [
                'logout_path' => static::PATH_LOGOUT,
                'target_url' => static::ROUTE_LOGIN,
                'priority' => 65,
            ],
            'users' => function () {
                return $this->userProvider;
            },
            'user_session_handler' => true,
        ];
    }

    /**
     * @return string
     */
    protected function getMerchantPortalRoutePattern(): string
    {
        if (APPLICATION == static::APPLICATION_MERCHANT_PORTAL) {
            return sprintf('(^/$|%s)', static::MERCHANT_PORTAL_ROUTE_PATTERN);
        }

        return static::MERCHANT_PORTAL_ROUTE_PATTERN;
    }
}
