<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Security\Loader\AuthenticatorManager\AuthenticatorManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface as SymfonyAuthenticatorManagerInterface;

class AuthenticationManagerServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_MANAGER = 'security.authentication_manager';

    /**
     * @var \Spryker\Yves\Security\Loader\AuthenticatorManager\AuthenticatorManagerInterface
     */
    protected AuthenticatorManagerInterface $authenticatorManager;

    /**
     * @param \Spryker\Yves\Security\Loader\AuthenticatorManager\AuthenticatorManagerInterface $authenticatorManager
     */
    public function __construct(AuthenticatorManagerInterface $authenticatorManager)
    {
        $this->authenticatorManager = $authenticatorManager;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_AUTHENTICATION_MANAGER, $container->protect(function (string $firewallName, array $options) use ($container): callable {
            return function () use ($container, $firewallName, $options): SymfonyAuthenticatorManagerInterface {
                return $this->authenticatorManager->create($container, $firewallName, $options);
            };
        }));

        return $container;
    }
}
