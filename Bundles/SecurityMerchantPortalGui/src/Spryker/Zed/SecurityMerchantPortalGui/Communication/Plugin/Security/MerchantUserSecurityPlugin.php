<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Form\MerchantLoginForm;
use Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class MerchantUserSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    protected const SECURITY_FIREWALL_NAME = 'MerchantUser';

    protected const ROUTE_LOGIN = 'security-merchant-portal-gui:login';
    protected const ROUTE_LOGOUT = '/security-merchant-portal-gui/logout';

    protected const IS_AUTHENTICATED_ANONYMOUSLY = 'IS_AUTHENTICATED_ANONYMOUSLY';

    /**
     * {@inheritDoc}
     * - Extends security service with Merchant Portal firewall.
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
            'pattern' => '^/(.+)-merchant-portal-gui/',
            'form' => [
                'login_path' => static::ROUTE_LOGIN,
                'check_path' => '/security-merchant-portal-gui/login_check',
                'username_parameter' => MerchantLoginForm::FORM_NAME . '[' . MerchantLoginForm::FIELD_USERNAME . ']',
                'password_parameter' => MerchantLoginForm::FORM_NAME . '[' . MerchantLoginForm::FIELD_PASSWORD . ']',
            ],
            'logout' => [
                'logout_path' => static::ROUTE_LOGOUT,
                'target_url' => static::ROUTE_LOGIN,
            ],
            'users' => function () {
                return $this->getFactory()->createMerchantUserProvider();
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
        $securityBuilder->addAccessRules([
            [
                '^/security-merchant-portal-gui',
                static::IS_AUTHENTICATED_ANONYMOUSLY,
            ],
            [
                '^/(.+)-merchant-portal-gui/',
                SecurityMerchantPortalGuiConfig::ROLE_MERCHANT_USER,
            ],
        ]);

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
            return $this->getFactory()->createMerchantUserAuthenticationSuccessHandler();
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
            return $this->getFactory()->createMerchantUserAuthenticationFailureHandler();
        });

        return $securityBuilder;
    }
}
