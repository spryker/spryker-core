<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiDependencyProvider;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Authenticator\AgentMerchantLoginFormAuthenticator;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Badge\MultiFactorAuthBadge;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Builder\OptionsBuilder;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Builder\OptionsBuilderInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Checker\SymfonyVersionChecker;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Checker\SymfonyVersionCheckerInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Expander\MerchantUserCriteriaExpander;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Expander\MerchantUserCriteriaExpanderInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Extender\SecurityBuilderAuthenticatorExtender;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Extender\SecurityBuilderAuthenticatorExtenderInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Extender\SecurityBuilderExtender;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Extender\SecurityBuilderExtenderInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Form\AgentMerchantLoginForm;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\AuditLogger;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\AuditLoggerInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\DataProvider\AuditLoggerUserProvider;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\DataProvider\AuditLoggerUserProviderInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler\AuthenticationFailureHandler;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Handler\AuthenticationSuccessHandler;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Provider\AgentMerchantUserProvider;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Subscriber\SwitchUserEventSubscriber;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUserInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Client\AgentSecurityMerchantPortalGuiToSessionClientInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToMessengerFacadeInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToRouterFacadeInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToSecurityFacadeInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToUserFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig getConfig()
 */
class AgentSecurityMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface<mixed>
     */
    public function createAgentMerchantLoginForm(): FormInterface
    {
        return $this->getFormFactory()->create(AgentMerchantLoginForm::class);
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Extender\SecurityBuilderExtenderInterface
     */
    public function createSecurityBuilderExtender(): SecurityBuilderExtenderInterface
    {
        return new SecurityBuilderExtender(
            $this->getConfig(),
            $this->createOptionsBuilder(),
            $this->createSwitchUserEventSubscriber(),
            $this->createSecurityBuilderAuthenticatorExtender(),
        );
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Builder\OptionsBuilderInterface
     */
    public function createOptionsBuilder(): OptionsBuilderInterface
    {
        return new OptionsBuilder(
            $this->getConfig(),
            $this->createAgentMerchantUserProvider(),
            $this->createSymfonyVersionChecker(),
        );
    }

    /**
     * @return \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    public function createAgentMerchantLoginFormAuthenticator(): AuthenticatorInterface
    {
        return new AgentMerchantLoginFormAuthenticator(
            $this->getConfig(),
            $this->createAgentMerchantUserProvider(),
            $this->createAuthenticationSuccessHandler(),
            $this->createAuthenticationFailureHandler(),
            $this->createMultiFactorAuthBadge(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUserInterface
     */
    public function createSecurityUser(UserTransfer $userTransfer): AgentMerchantUserInterface
    {
        return new AgentMerchantUser(
            $userTransfer,
            [
                $this->getConfig()->getRoleMerchantAgent(),
                $this->getConfig()->getRoleAllowedToSwitch(),
            ],
        );
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    public function createAgentMerchantUserProvider(): UserProviderInterface
    {
        return new AgentMerchantUserProvider();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createSwitchUserEventSubscriber(): EventSubscriberInterface
    {
        return new SwitchUserEventSubscriber();
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Extender\SecurityBuilderAuthenticatorExtenderInterface
     */
    public function createSecurityBuilderAuthenticatorExtender(): SecurityBuilderAuthenticatorExtenderInterface
    {
        return new SecurityBuilderAuthenticatorExtender(
            $this->getConfig(),
            $this->createAgentMerchantLoginFormAuthenticator(),
            $this->createAuthenticationSuccessHandler(),
            $this->createAuthenticationFailureHandler(),
            $this->createSymfonyVersionChecker(),
        );
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface
     */
    public function createAuthenticationSuccessHandler(): AuthenticationSuccessHandlerInterface
    {
        return new AuthenticationSuccessHandler();
    }

    /**
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface
     */
    public function createAuthenticationFailureHandler(): AuthenticationFailureHandlerInterface
    {
        return new AuthenticationFailureHandler();
    }

    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Checker\SymfonyVersionCheckerInterface
     */
    public function createSymfonyVersionChecker(): SymfonyVersionCheckerInterface
    {
        return new SymfonyVersionChecker();
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Expander\MerchantUserCriteriaExpanderInterface
     */
    public function createMerchantUserCriteriaExpander(): MerchantUserCriteriaExpanderInterface
    {
        return new MerchantUserCriteriaExpander(
            $this->getConfig(),
            $this->getAuthorizationCheckerService(),
        );
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\AuditLoggerInterface
     */
    public function createAuditLogger(): AuditLoggerInterface
    {
        return new AuditLogger($this->createAuditLoggerUserProvider());
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Logger\DataProvider\AuditLoggerUserProviderInterface
     */
    public function createAuditLoggerUserProvider(): AuditLoggerUserProviderInterface
    {
        return new AuditLoggerUserProvider($this->getTokenStorageService());
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToMessengerFacadeInterface
     */
    public function getMessengerFacade(): AgentSecurityMerchantPortalGuiToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToSecurityFacadeInterface
     */
    public function getSecurityFacade(): AgentSecurityMerchantPortalGuiToSecurityFacadeInterface
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::FACADE_SECURITY);
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): AgentSecurityMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToUserFacadeInterface
     */
    public function getUserFacade(): AgentSecurityMerchantPortalGuiToUserFacadeInterface
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade\AgentSecurityMerchantPortalGuiToRouterFacadeInterface
     */
    public function getRouterFacade(): AgentSecurityMerchantPortalGuiToRouterFacadeInterface
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::FACADE_ROUTER);
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    public function getAuthorizationCheckerService(): AuthorizationCheckerInterface
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
    }

    /**
     * @return \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    public function getTokenStorageService(): TokenStorageInterface
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::SERVICE_SECURITY_TOKEN_STORAGE);
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Badge\MultiFactorAuthBadge
     */
    public function createMultiFactorAuthBadge(): MultiFactorAuthBadge
    {
        return new MultiFactorAuthBadge(
            $this->getMerchantAgentUserMultiFactorAuthenticationHandlerPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\AuthenticationHandlerPluginInterface>
     */
    public function getMerchantAgentUserMultiFactorAuthenticationHandlerPlugins(): array
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::PLUGINS_MERCHANT_AGENT_USER_AUTHENTICATION_HANDLER);
    }

    /**
     * @return \Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Client\AgentSecurityMerchantPortalGuiToSessionClientInterface
     */
    public function getSessionClient(): AgentSecurityMerchantPortalGuiToSessionClientInterface
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    public function getZedUiFactory(): ZedUiFactoryInterface
    {
        return $this->getProvidedDependency(AgentSecurityMerchantPortalGuiDependencyProvider::SERVICE_ZED_UI_FACTORY);
    }
}
