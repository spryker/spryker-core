<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Builder;

use Spryker\Zed\SecurityGui\Communication\Form\LoginForm;
use Spryker\Zed\SecurityGui\SecurityGuiConfig;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SecurityGuiOptionsBuilder implements SecurityGuiOptionsBuilderInterface
{
    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Expander\SecurityBuilderExpander::SECURITY_USER_LOGIN_FORM_AUTHENTICATOR
     *
     * @var string
     */
    protected const SECURITY_USER_LOGIN_FORM_AUTHENTICATOR = 'security.User.login_form.authenticator';

    /**
     * @var string
     */
    protected const ROUTE_LOGIN = 'security-gui:login';

    /**
     * @var string
     */
    protected const PATH_LOGIN_CHECK = '/login_check';

    /**
     * @var string
     */
    protected const PATH_LOGOUT = '/auth/logout';

    /**
     * @var \Spryker\Zed\SecurityGui\SecurityGuiConfig
     */
    protected SecurityGuiConfig $config;

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected UserProviderInterface $userProvider;

    /**
     * @param \Spryker\Zed\SecurityGui\SecurityGuiConfig $config
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     */
    public function __construct(
        SecurityGuiConfig $config,
        UserProviderInterface $userProvider
    ) {
        $this->config = $config;
        $this->userProvider = $userProvider;
    }

    /**
     * @return array<mixed>
     */
    public function buildOptions(): array
    {
        return [
            'pattern' => $this->config->getBackofficeRoutePattern(),
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => static::PATH_LOGIN_CHECK,
                'username_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_USERNAME . ']',
                'password_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_PASSWORD . ']',
                'authenticators' => [
                    static::SECURITY_USER_LOGIN_FORM_AUTHENTICATOR,
                ],
            ],
            'logout' => [
                'logout_path' => static::PATH_LOGOUT,
                'target_url' => static::ROUTE_LOGIN,
            ],
            'users' => function () {
                return $this->userProvider;
            },
            'user_session_handler' => true,
        ];
    }
}
