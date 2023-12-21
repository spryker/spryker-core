<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Loader\AuthenticatorManager;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManager as SymfonyAuthenticatorManager;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface as SymfonyAuthenticatorManagerInterface;

class AuthenticatorManager implements AuthenticatorManagerInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var string
     */
    protected const SERVICE_LOGGER = 'logger';

    /**
     * @uses \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     *
     * @var string
     */
    protected const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * @var string
     */
    protected const KEY_AUTHENTICATORS = 'authenticators';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param string $firewallName
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface
     */
    public function create(
        ContainerInterface $container,
        string $firewallName,
        array $options
    ): SymfonyAuthenticatorManagerInterface {
        $authenticators = [];

        if (isset($options[static::KEY_AUTHENTICATORS])) {
            foreach ($options[static::KEY_AUTHENTICATORS] as $authenticatorId) {
                $authenticators[] = $container->get($authenticatorId);
            }
        }

        $authManager = new SymfonyAuthenticatorManager(
            $authenticators,
            $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
            $container->get(static::SERVICE_DISPATCHER),
            $firewallName,
            $container->has(static::SERVICE_LOGGER) ? $container->get(static::SERVICE_LOGGER) : null,
        );

        return $authManager;
    }
}
