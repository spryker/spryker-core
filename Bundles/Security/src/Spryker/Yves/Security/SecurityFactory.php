<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security;

use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
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
     * @return \Spryker\Shared\Security\Configuration\SecurityConfiguration
     */
    public function createSecurityConfiguration(): SecurityConfiguration
    {
        return new SecurityConfiguration();
    }
}
