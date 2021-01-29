<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication;

use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Security\SecurityDependencyProvider;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
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
 * @method \Spryker\Zed\Security\Business\SecurityFacadeInterface getFacade()
 * @method \Spryker\Zed\Security\SecurityConfig getConfig()
 */
class SecurityCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface[]
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
        if (class_exists(NativePasswordEncoder::class)) {
            // Support of symfony/security-core ^5.0
            return new NativePasswordEncoder(null, null, $this->getConfig()->getBCryptCost());
        }

        return new BCryptPasswordEncoder($this->getConfig()->getBCryptCost());
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
            $this->getConfig()->getHttpsPort()
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
}
