<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security;

use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Security\AuthenticationListener\AuthenticationListener;
use Spryker\Yves\Security\AuthenticationListener\AuthenticationListenerInterface;
use Spryker\Yves\Security\Booter\SecurityApplicationBooter;
use Spryker\Yves\Security\Booter\SecurityApplicationBooterInterface;
use Spryker\Yves\Security\Configurator\SecurityConfigurator;
use Spryker\Yves\Security\Configurator\SecurityConfiguratorInterface;
use Spryker\Yves\Security\Loader\AuthenticatorManager\AuthenticatorManager;
use Spryker\Yves\Security\Loader\AuthenticatorManager\AuthenticatorManagerInterface;
use Spryker\Yves\Security\Loader\Services\AccessListenerServiceLoader;
use Spryker\Yves\Security\Loader\Services\AccessManagerServiceLoader;
use Spryker\Yves\Security\Loader\Services\AccessMapServiceLoader;
use Spryker\Yves\Security\Loader\Services\AuthenticationListenerFactoriesServiceLoader;
use Spryker\Yves\Security\Loader\Services\AuthenticationListenerPrototypesServiceLoader;
use Spryker\Yves\Security\Loader\Services\AuthenticationManagerServiceLoader;
use Spryker\Yves\Security\Loader\Services\AuthorizationCheckerServiceLoader;
use Spryker\Yves\Security\Loader\Services\ChannelListenerServiceLoader;
use Spryker\Yves\Security\Loader\Services\EncoderServiceLoader;
use Spryker\Yves\Security\Loader\Services\EntryPointPrototypesServiceLoader;
use Spryker\Yves\Security\Loader\Services\FirewallServiceLoader;
use Spryker\Yves\Security\Loader\Services\LastErrorServiceLoader;
use Spryker\Yves\Security\Loader\Services\ListenerPrototypeServiceLoader;
use Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface;
use Spryker\Yves\Security\Loader\Services\TokenStorageServiceLoader;
use Spryker\Yves\Security\Loader\Services\TrustResolverServiceLoader;
use Spryker\Yves\Security\Loader\Services\UserCheckerServiceLoader;
use Spryker\Yves\Security\Loader\Services\UserProviderPrototypeServiceLoader;
use Spryker\Yves\Security\Loader\Services\UserServiceLoader;
use Spryker\Yves\Security\Loader\Services\UtilsServiceLoader;
use Spryker\Yves\Security\Loader\Services\VotersServiceLoader;
use Spryker\Yves\Security\Loader\ServicesLoader;
use Spryker\Yves\Security\Loader\ServicesLoaderInterface;
use Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin;
use Spryker\Yves\Security\Plugin\Validator\UserPasswordValidatorConstraintPlugin;
use Spryker\Yves\Security\Router\SecurityRouterInterface;
use Spryker\Yves\Security\Subscriber\SecurityDispatcherSubscriber;
use Spryker\Yves\Security\Subscriber\SecurityDispatcherSubscriberInterface;
use Spryker\Yves\Security\Validator\UserPasswordValidatorConstraint;
use Spryker\Yves\Security\Validator\UserPasswordValidatorConstraintInterface;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

/**
 * @method \Spryker\Yves\Security\SecurityConfig getConfig()
 */
class SecurityFactory extends AbstractFactory
{
    /**
     * @var \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin|null
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
     * @return \Symfony\Component\PasswordHasher\PasswordHasherInterface
     */
    public function createPasswordHasher(): PasswordHasherInterface
    {
        return new NativePasswordHasher(null, null, $this->getConfig()->getBCryptCost());
    }

    /**
     * @return \Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface
     */
    public function createSessionStrategy(): SessionAuthenticationStrategyInterface
    {
        return new SessionAuthenticationStrategy(SessionAuthenticationStrategy::MIGRATE);
    }

    /**
     * @return list<\Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityAuthenticationListenerFactoryTypeExpanderPluginInterface>
     */
    public function getSecurityAuthenticationListenerFactoryTypeExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SecurityDependencyProvider::PLUGINS_SECURITY_AUTHENTICATION_LISTENER_FACTORY_TYPE_EXPANDER);
    }

    /**
     * @return \Spryker\Shared\Security\Configuration\SecurityConfiguration
     */
    public function createSecurityConfiguration(): SecurityConfiguration
    {
        return new SecurityConfiguration();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\ServicesLoaderInterface
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
     * @return \Spryker\Yves\Security\Booter\SecurityApplicationBooterInterface
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
     * @return \Spryker\Yves\Security\Subscriber\SecurityDispatcherSubscriberInterface
     */
    public function createSecurityDispatcherSubscriber(): SecurityDispatcherSubscriberInterface
    {
        return new SecurityDispatcherSubscriber(
            $this->createSecurityConfigurator(),
        );
    }

    /**
     * @return \Spryker\Yves\Security\Configurator\SecurityConfiguratorInterface
     */
    public function createSecurityConfigurator(): SecurityConfiguratorInterface
    {
        return new SecurityConfigurator(
            $this->createSecurityConfiguration(),
            $this->getSecurityPlugins(),
        );
    }

    /**
     * @return \Spryker\Yves\Security\Router\SecurityRouterInterface
     */
    public function getSecurityRouter(): SecurityRouterInterface
    {
        return $this->getProvidedDependency(SecurityDependencyProvider::SERVICE_SECURITY_ROUTERS);
    }

    /**
     * @return \Spryker\Yves\Security\AuthenticationListener\AuthenticationListenerInterface
     */
    public function createAuthenticationListener(): AuthenticationListenerInterface
    {
        return new AuthenticationListener(
            $this->getSecurityAuthenticationListenerFactoryTypeExpanderPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface>
     */
    public function getServiceLoaders(): array
    {
        return [
            $this->createAuthorizationCheckerServiceLoader(),
            $this->createTokenStorageServiceLoader(),
            $this->createUserServiceLoader(),
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
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createAuthorizationCheckerServiceLoader(): ServiceLoaderInterface
    {
        return new AuthorizationCheckerServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createTokenStorageServiceLoader(): ServiceLoaderInterface
    {
        return new TokenStorageServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createUserServiceLoader(): ServiceLoaderInterface
    {
        return new UserServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createAuthenticationManagerServiceLoader(): ServiceLoaderInterface
    {
        return new AuthenticationManagerServiceLoader(
            $this->createAuthenticatorManager(),
        );
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createEncoderServiceLoader(): ServiceLoaderInterface
    {
        return new EncoderServiceLoader(
            $this->createPasswordHasher(),
        );
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createUserCheckerServiceLoader(): ServiceLoaderInterface
    {
        return new UserCheckerServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createAccessManagerServiceLoader(): ServiceLoaderInterface
    {
        return new AccessManagerServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createVotersServiceLoader(): ServiceLoaderInterface
    {
        return new VotersServiceLoader(
            $this->createSecurityConfigurator(),
        );
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createFirewallServiceLoader(): ServiceLoaderInterface
    {
        return new FirewallServiceLoader(
            $this->createSecurityConfigurator(),
            $this->createAuthenticationListener(),
        );
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createChannelListenerServiceLoader(): ServiceLoaderInterface
    {
        return new ChannelListenerServiceLoader(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createAuthenticationListenerFactoriesServiceLoader(): ServiceLoaderInterface
    {
        return new AuthenticationListenerFactoriesServiceLoader(
            $this->createAuthenticationListener(),
        );
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createAccessListenerServiceLoader(): ServiceLoaderInterface
    {
        return new AccessListenerServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createAccessMapServiceLoader(): ServiceLoaderInterface
    {
        return new AccessMapServiceLoader(
            $this->createSecurityConfigurator(),
        );
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createTrustResolverServiceLoader(): ServiceLoaderInterface
    {
        return new TrustResolverServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createUtilsServiceLoader(): ServiceLoaderInterface
    {
        return new UtilsServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createLastErrorServiceLoader(): ServiceLoaderInterface
    {
        return new LastErrorServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createUserProviderPrototypeServiceLoader(): ServiceLoaderInterface
    {
        return new UserProviderPrototypeServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createListenerPrototypeServiceLoader(): ServiceLoaderInterface
    {
        return new ListenerPrototypeServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
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
     * @return \Spryker\Yves\Security\Loader\Services\ServiceLoaderInterface
     */
    public function createEntryPointPrototypesServiceLoader(): ServiceLoaderInterface
    {
        return new EntryPointPrototypesServiceLoader();
    }

    /**
     * @return \Spryker\Yves\Security\Validator\UserPasswordValidatorConstraintInterface
     */
    public function createUserPasswordValidatorConstraint(): UserPasswordValidatorConstraintInterface
    {
        if (class_exists(AuthenticationProviderManager::class) === true) {
            return new UserPasswordValidatorConstraintPlugin();
        }

        return new UserPasswordValidatorConstraint();
    }

    /**
     * @return \Spryker\Yves\Security\Loader\AuthenticatorManager\AuthenticatorManagerInterface
     */
    public function createAuthenticatorManager(): AuthenticatorManagerInterface
    {
        return new AuthenticatorManager();
    }
}
