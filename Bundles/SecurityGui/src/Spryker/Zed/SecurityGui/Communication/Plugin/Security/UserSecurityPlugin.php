<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityGui\Communication\Form\LoginForm;
use Spryker\Zed\SecurityGui\SecurityGuiConfig;

/**
 * @method \Spryker\Zed\SecurityGui\Communication\SecurityGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityGui\Business\SecurityGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SecurityGui\SecurityGuiConfig getConfig()
 */
class UserSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    protected const SECURITY_FIREWALL_NAME = 'User';

    protected const ROUTE_LOGIN = 'security-gui:login';
    protected const PATH_LOGOUT = '/auth/logout';

    protected const IS_AUTHENTICATED_ANONYMOUSLY = 'IS_AUTHENTICATED_ANONYMOUSLY';

    /**
     * @uses \Spryker\Zed\Router\Communication\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     */
    protected const SERVICE_ROUTER = 'routers';

    /**
     * {@inheritDoc}
     * - Extends security service with User firewall.
     *
     * @api
     *
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $securityBuilder = $this->addFirewall($securityBuilder);
        $securityBuilder = $this->addAccessRules($securityBuilder);

        $securityBuilder = $this->addAuthenticationSuccessHandler($securityBuilder);
        $securityBuilder = $this->addAuthenticationFailureHandler($securityBuilder);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addFirewall(static::SECURITY_FIREWALL_NAME, [
            'anonymous' => true,
            'pattern' => $this->getConfig()->getBackofficeRoutePattern(),
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => '/login_check',
                'username_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_USERNAME . ']',
                'password_parameter' => LoginForm::FORM_NAME . '[' . LoginForm::FIELD_PASSWORD . ']',
            ],
            'logout' => [
                'logout_path' => static::PATH_LOGOUT,
                'target_url' => static::ROUTE_LOGIN,
            ],
            'users' => function () {
                return $this->getFactory()->createUserProvider();
            },
            'user_session_handler' => true,
        ]);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAccessRules(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $accessRules = [
            [
                $this->getConfig()->getIgnorablePaths(),
                static::IS_AUTHENTICATED_ANONYMOUSLY,
            ],
            [
                $this->getConfig()->getBackofficeRoutePattern(),
                SecurityGuiConfig::ROLE_BACK_OFFICE_USER,
            ],
        ];

        $securityBuilder->addAccessRules($accessRules);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationSuccessHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAuthenticationSuccessHandler(static::SECURITY_FIREWALL_NAME, function () {
            return $this->getFactory()->createUserAuthenticationSuccessHandler();
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationFailureHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAuthenticationFailureHandler(static::SECURITY_FIREWALL_NAME, function (ContainerInterface $container) {
            return $this->getFactory()->createUserAuthenticationFailureHandler();
        });

        return $securityBuilder;
    }
}
