<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Communication\Expander;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Zed\SecurityGui\Communication\Builder\SecurityGuiOptionsBuilderInterface;
use Spryker\Zed\SecurityGui\SecurityGuiConfig;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class SecurityBuilderExpander implements SecurityBuilderExpanderInterface
{
    /**
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'User';

    /**
     * @var string
     */
    protected const ACCESS_MODE_PUBLIC = 'PUBLIC_ACCESS';

    /**
     * @var string
     */
    protected const SECURITY_USER_LOGIN_FORM_AUTHENTICATOR = 'security.User.login_form.authenticator';

    /**
     * @var \Spryker\Zed\SecurityGui\Communication\Builder\SecurityGuiOptionsBuilderInterface
     */
    protected SecurityGuiOptionsBuilderInterface $optionsBuilder;

    /**
     * @var \Spryker\Zed\SecurityGui\SecurityGuiConfig
     */
    protected SecurityGuiConfig $config;

    /**
     * @var \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    protected AuthenticatorInterface $authenticator;

    /**
     * @param \Spryker\Zed\SecurityGui\Communication\Builder\SecurityGuiOptionsBuilderInterface $optionsBuilder
     * @param \Spryker\Zed\SecurityGui\SecurityGuiConfig $config
     * @param \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface $authenticator
     */
    public function __construct(
        SecurityGuiOptionsBuilderInterface $optionsBuilder,
        SecurityGuiConfig $config,
        AuthenticatorInterface $authenticator
    ) {
        $this->optionsBuilder = $optionsBuilder;
        $this->config = $config;
        $this->authenticator = $authenticator;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $securityBuilder = $this->addFirewalls($securityBuilder);
        $securityBuilder = $this->addAccessRules($securityBuilder);
        $this->addAuthenticator($container);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addFirewall(
            static::SECURITY_FIREWALL_NAME,
            $this->optionsBuilder->buildOptions(),
        );
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAccessRules(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addAccessRules([
            [
                $this->config->getIgnorablePaths(),
                static::ACCESS_MODE_PUBLIC,
            ],
            [
                $this->config->getBackofficeRoutePattern(),
                SecurityGuiConfig::ROLE_BACK_OFFICE_USER,
            ],
        ]);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    protected function addAuthenticator(ContainerInterface $container): void
    {
        $container->set(static::SECURITY_USER_LOGIN_FORM_AUTHENTICATOR, function () {
            return $this->authenticator;
        });
    }
}
