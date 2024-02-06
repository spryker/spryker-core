<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Extender;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Checker\SymfonyVersionCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class SecurityBuilderAuthenticatorExtender implements SecurityBuilderAuthenticatorExtenderInterface
{
    /**
     * @var \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig
     */
    protected AgentSecurityMerchantPortalGuiConfig $agentSecurityMerchantPortalGuiConfig;

    /**
     * @var \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    protected AuthenticatorInterface $agentMerchantLoginFormAuthenticator;

    /**
     * @var \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface
     */
    protected AuthenticationSuccessHandlerInterface $authenticationSuccessHandler;

    /**
     * @var \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface
     */
    protected AuthenticationFailureHandlerInterface $authenticationFailureHandler;

    /**
     * @var \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Checker\SymfonyVersionCheckerInterface
     */
    protected SymfonyVersionCheckerInterface $symfonyVersionChecker;

    /**
     * @param \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig $agentSecurityMerchantPortalGuiConfig
     * @param \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface $agentMerchantLoginFormAuthenticator
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface $authenticationSuccessHandler
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface $authenticationFailureHandler
     * @param \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Checker\SymfonyVersionCheckerInterface $symfonyVersionChecker
     */
    public function __construct(
        AgentSecurityMerchantPortalGuiConfig $agentSecurityMerchantPortalGuiConfig,
        AuthenticatorInterface $agentMerchantLoginFormAuthenticator,
        AuthenticationSuccessHandlerInterface $authenticationSuccessHandler,
        AuthenticationFailureHandlerInterface $authenticationFailureHandler,
        SymfonyVersionCheckerInterface $symfonyVersionChecker
    ) {
        $this->agentSecurityMerchantPortalGuiConfig = $agentSecurityMerchantPortalGuiConfig;
        $this->agentMerchantLoginFormAuthenticator = $agentMerchantLoginFormAuthenticator;
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
        $this->authenticationFailureHandler = $authenticationFailureHandler;
        $this->symfonyVersionChecker = $symfonyVersionChecker;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        if ($this->symfonyVersionChecker->isSymfonyVersion5()) {
            $securityBuilder = $this->addAuthenticationSuccessHandler($securityBuilder);
            $securityBuilder = $this->addAuthenticationFailureHandler($securityBuilder);

            return $securityBuilder;
        }

        $container->set($this->agentSecurityMerchantPortalGuiConfig->getSecurityAgentMerchantPortalLoginFormAuthenticatorName(), function () {
            return $this->agentMerchantLoginFormAuthenticator;
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAuthenticationSuccessHandler(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addAuthenticationSuccessHandler($this->agentSecurityMerchantPortalGuiConfig->getSecurityFirewallName(), function () {
            return $this->authenticationSuccessHandler;
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
        $securityBuilder->addAuthenticationFailureHandler($this->agentSecurityMerchantPortalGuiConfig->getSecurityFirewallName(), function () {
            return $this->authenticationFailureHandler;
        });

        return $securityBuilder;
    }
}
