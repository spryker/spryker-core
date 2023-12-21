<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication;

use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Security\Communication\Booter\SecurityApplicationBooter;
use Spryker\Zed\Security\Communication\Booter\SecurityApplicationBooterInterface;
use Spryker\Zed\Security\Communication\Configurator\SecurityConfigurator;
use Spryker\Zed\Security\Communication\Configurator\SecurityConfiguratorInterface;
use Spryker\Zed\Security\Communication\Loader\AuthenticatorManager\AuthenticatorManager;
use Spryker\Zed\Security\Communication\Loader\AuthenticatorManager\AuthenticatorManagerInterface;
use Spryker\Zed\Security\Communication\Loader\Services\AccessListenerServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\AccessManagerServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\AccessMapServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\AuthenticationListenerFactoriesServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\AuthenticationListenerPrototypesServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\AuthenticationManagerServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\AuthorizationCheckerServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\ChannelListenerServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\EncoderServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\EntryPointPrototypesServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\FirewallServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\LastErrorServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\ListenerPrototypeServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface;
use Spryker\Zed\Security\Communication\Loader\Services\TokenStorageServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\TrustResolverServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\UserCheckerServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\UserProviderPrototypeServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\UtilsServiceLoader;
use Spryker\Zed\Security\Communication\Loader\Services\VotersServiceLoader;
use Spryker\Zed\Security\Communication\Loader\ServicesLoader;
use Spryker\Zed\Security\Communication\Loader\ServicesLoaderInterface;
use Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin;
use Spryker\Zed\Security\Communication\Plugin\Validator\UserPasswordValidatorConstraintPlugin;
use Spryker\Zed\Security\Communication\Router\SecurityRouterInterface;
use Spryker\Zed\Security\Communication\Subscriber\SecurityDispatcherSubscriber;
use Spryker\Zed\Security\Communication\Subscriber\SecurityDispatcherSubscriberInterface;
use Spryker\Zed\Security\Communication\Validator\UserPasswordValidatorConstraint;
use Spryker\Zed\Security\Communication\Validator\UserPasswordValidatorConstraintInterface;
use Spryker\Zed\Security\SecurityDependencyProvider;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Http\AccessMap;
use Symfony\Component\Security\Http\AccessMapInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\EntryPoint\RetryAuthenticationEntryPoint;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Http\Logout\SessionLogoutHandler;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

/**
 * @method \Spryker\Zed\Security\Communication\SecurityFacadeInterface getFacade()
 * @method \Spryker\Zed\Security\SecurityConfig getConfig()
 */
class SecurityCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @var \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin|null
     */
    protected $securityApplicationPluginCache;

    /**
     * @return array<\Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface>
     */
    public function getSecurityPlugins(): array
    {
        return $this->getProvidedDependency(SecurityDependencyProvider::PLUGINS_SECURITY);
    }

    /**
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    public function createPasswordEncoder(): PasswordEncoderInterface
    {
        return new NativePasswordEncoder(null, null, $this->getConfig()->getBCryptCost());
    }

    /**
     * @return \Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface
     */
    public function createSessionStrategy(): SessionAuthenticationStrategyInterface
    {
        return new SessionAuthenticationStrategy(SessionAuthenticationStrategy::MIGRATE);
    }

    /**
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function createSecurityConfiguration(): SecurityBuilderInterface
    {
        return new SecurityConfiguration();
    }

    /**
     * @return \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    public function createTokenStorage(): TokenStorageInterface
    {
        return new TokenStorage();
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserCheckerInterface
     */
    public function createUserChecker(): UserCheckerInterface
    {
        return new UserChecker();
    }

    /**
     * @return \Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface
     */
    public function createRetryAuthenticationEntryPoint(): AuthenticationEntryPointInterface
    {
        return new RetryAuthenticationEntryPoint(
            $this->getConfig()->getHttpPort(),
            $this->getConfig()->getHttpsPort(),
        );
    }

    /**
     * @return \Symfony\Component\Security\Http\AccessMapInterface
     */
    public function createAccessMap(): AccessMapInterface
    {
        return new AccessMap();
    }

    /**
     * @return \Symfony\Component\Security\Http\Logout\LogoutHandlerInterface
     */
    public function createSessionLogoutHandler(): LogoutHandlerInterface
    {
        return new SessionLogoutHandler();
    }

    /**
     * @return \Symfony\Component\Config\Loader\Loader
     */
    public function createClosureLoader(): Loader
    {
        return new ClosureLoader();
    }

    /**
     * @return \IteratorAggregate|\Countable
     */
    public function createRouteCollection()
    {
        return new RouteCollection();
    }

    /**
     * @return \Symfony\Component\PasswordHasher\PasswordHasherInterface
     */
    public function createPasswordHasher(): PasswordHasherInterface
    {
        return new NativePasswordHasher(null, null, $this->getConfig()->getBCryptCost());
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Configurator\SecurityConfiguratorInterface
     */
    public function createSecurityConfigurator(): SecurityConfiguratorInterface
    {
        return new SecurityConfigurator(
            $this->createSecurityConfiguration(),
            $this->getSecurityPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Router\SecurityRouterInterface
     */
    public function getSecurityRouter(): SecurityRouterInterface
    {
        return $this->getProvidedDependency(SecurityDependencyProvider::SECURITY_ROUTERS);
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Subscriber\SecurityDispatcherSubscriberInterface
     */
    public function createSecurityDispatcherSubscriber(): SecurityDispatcherSubscriberInterface
    {
        return new SecurityDispatcherSubscriber(
            $this->createSecurityConfigurator(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\ServicesLoaderInterface
     */
    public function createServicesLoader(): ServicesLoaderInterface
    {
        if (class_exists(AuthenticationProviderManager::class) === true) {
            if ($this->securityApplicationPluginCache === null) {
                $this->securityApplicationPluginCache = new SecurityApplicationPlugin();
            }

            return $this->securityApplicationPluginCache;
        }

        return new ServicesLoader(
            $this->getServiceLoaders(),
        );
    }

    /**
     * @return array<\Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface>
     */
    public function getServiceLoaders(): array
    {
        return [
            $this->createAuthorizationCheckerServiceLoader(),
            $this->createTokenStorageServiceLoader(),
            $this->createAuthenticationManagerServiceLoader(),
            $this->createEncoderServiceLoader(),
            $this->createUserCheckerServiceLoader(),
            $this->createAccessManagerServiceLoader(),
            $this->createVotersServiceLoader(),
            $this->createFirewallServiceLoader(),
            $this->createChannelListenerServiceLoader(),
            $this->createAuthenticationListenerFactoriesServiceLoader(),
            $this->createAccessListenerServiceLoader(),
            $this->createAccessMapServiceLoader(),
            $this->createTrustResolverServiceLoader(),
            $this->createUtilsServiceLoader(),
            $this->createLastErrorServiceLoader(),
            $this->createUserProviderPrototypeServiceLoader(),
            $this->createListenerPrototypeServiceLoader(),
            $this->createAuthenticationListenerPrototypesServiceLoader(),
            $this->createEntryPointPrototypesServiceLoader(),
        ];
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createAuthorizationCheckerServiceLoader(): ServiceLoaderInterface
    {
        return new AuthorizationCheckerServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createTokenStorageServiceLoader(): ServiceLoaderInterface
    {
        return new TokenStorageServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createAuthenticationManagerServiceLoader(): ServiceLoaderInterface
    {
        return new AuthenticationManagerServiceLoader(
            $this->createAuthenticatorManager(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createEncoderServiceLoader(): ServiceLoaderInterface
    {
        return new EncoderServiceLoader(
            $this->createPasswordHasher(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createUserCheckerServiceLoader(): ServiceLoaderInterface
    {
        return new UserCheckerServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createAccessManagerServiceLoader(): ServiceLoaderInterface
    {
        return new AccessManagerServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createVotersServiceLoader(): ServiceLoaderInterface
    {
        return new VotersServiceLoader(
            $this->createSecurityConfigurator(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createFirewallServiceLoader(): ServiceLoaderInterface
    {
        return new FirewallServiceLoader(
            $this->createSecurityConfigurator(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createChannelListenerServiceLoader(): ServiceLoaderInterface
    {
        return new ChannelListenerServiceLoader(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createAuthenticationListenerFactoriesServiceLoader(): ServiceLoaderInterface
    {
        return new AuthenticationListenerFactoriesServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createAccessListenerServiceLoader(): ServiceLoaderInterface
    {
        return new AccessListenerServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createAccessMapServiceLoader(): ServiceLoaderInterface
    {
        return new AccessMapServiceLoader(
            $this->createSecurityConfigurator(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createTrustResolverServiceLoader(): ServiceLoaderInterface
    {
        return new TrustResolverServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createUtilsServiceLoader(): ServiceLoaderInterface
    {
        return new UtilsServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createLastErrorServiceLoader(): ServiceLoaderInterface
    {
        return new LastErrorServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createUserProviderPrototypeServiceLoader(): ServiceLoaderInterface
    {
        return new UserProviderPrototypeServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createListenerPrototypeServiceLoader(): ServiceLoaderInterface
    {
        return new ListenerPrototypeServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createAuthenticationListenerPrototypesServiceLoader(): ServiceLoaderInterface
    {
        return new AuthenticationListenerPrototypesServiceLoader(
            $this->createSecurityConfigurator(),
            $this->getSecurityRouter(),
            $this->createAuthenticatorManager(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\Services\ServiceLoaderInterface
     */
    public function createEntryPointPrototypesServiceLoader(): ServiceLoaderInterface
    {
        return new EntryPointPrototypesServiceLoader();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Validator\UserPasswordValidatorConstraintInterface
     */
    public function createUserPasswordValidatorConstraint(): UserPasswordValidatorConstraintInterface
    {
        if (class_exists(AuthenticationProviderManager::class) === true) {
            return new UserPasswordValidatorConstraintPlugin();
        }

        return new UserPasswordValidatorConstraint();
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Booter\SecurityApplicationBooterInterface
     */
    public function createSecurityApplicationBooter(): SecurityApplicationBooterInterface
    {
        if (class_exists(AuthenticationProviderManager::class) === true) {
            if ($this->securityApplicationPluginCache === null) {
                $this->securityApplicationPluginCache = new SecurityApplicationPlugin();
            }

            return $this->securityApplicationPluginCache;
        }

        return new SecurityApplicationBooter(
            $this->createSecurityDispatcherSubscriber(),
            $this->getSecurityRouter(),
        );
    }

    /**
     * @return \Spryker\Zed\Security\Communication\Loader\AuthenticatorManager\AuthenticatorManagerInterface
     */
    public function createAuthenticatorManager(): AuthenticatorManagerInterface
    {
        return new AuthenticatorManager();
    }
}
