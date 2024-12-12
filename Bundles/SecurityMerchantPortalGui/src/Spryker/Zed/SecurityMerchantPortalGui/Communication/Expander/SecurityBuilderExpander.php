<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Expander;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Builder\OptionsBuilderInterface;
use Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class SecurityBuilderExpander implements SecurityBuilderExpanderInterface
{
    /**
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'MerchantUser';

    /**
     * @var string
     */
    protected const ACCESS_MODE_PUBLIC = 'PUBLIC_ACCESS';

    /**
     * @var string
     */
    protected const APPLICATION_MERCHANT_PORTAL = 'MERCHANT_PORTAL';

    /**
     * @var string
     */
    protected const SECURITY_MERCHANT_PORTAL_LOGIN_FORM_AUTHENTICATOR = 'security.MerchantUser.login_form.authenticator';

    /**
     * @var \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    protected AuthenticatorInterface $authenticator;

    /**
     * @var \Spryker\Zed\SecurityMerchantPortalGui\Communication\Builder\OptionsBuilderInterface
     */
    protected OptionsBuilderInterface $optionsBuilder;

    /**
     * @var \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig
     */
    protected SecurityMerchantPortalGuiConfig $config;

    /**
     * @param \Spryker\Zed\SecurityMerchantPortalGui\Communication\Builder\OptionsBuilderInterface $optionsBuilder
     * @param \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface $authenticator
     * @param \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig $config
     */
    public function __construct(
        OptionsBuilderInterface $optionsBuilder,
        AuthenticatorInterface $authenticator,
        SecurityMerchantPortalGuiConfig $config
    ) {
        $this->optionsBuilder = $optionsBuilder;
        $this->authenticator = $authenticator;
        $this->config = $config;
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
                $this->config->getIgnorablePathPattern(),
                static::ACCESS_MODE_PUBLIC,
            ],
            [
                $this->getMerchantPortalRoutePattern(),
                SecurityMerchantPortalGuiConfig::ROLE_MERCHANT_USER,
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
        $container->set(static::SECURITY_MERCHANT_PORTAL_LOGIN_FORM_AUTHENTICATOR, function () {
            return $this->authenticator;
        });
    }

    /**
     * @return string
     */
    protected function getMerchantPortalRoutePattern(): string
    {
        if (APPLICATION == static::APPLICATION_MERCHANT_PORTAL) {
            return sprintf('(^/$|%s)', $this->config->getMerchantPortalRoutePattern());
        }

        return $this->config->getMerchantPortalRoutePattern();
    }
}
