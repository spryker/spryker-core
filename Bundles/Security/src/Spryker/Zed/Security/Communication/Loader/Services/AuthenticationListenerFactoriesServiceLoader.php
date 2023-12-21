<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\Services;

use Spryker\Service\Container\ContainerInterface;

class AuthenticationListenerFactoriesServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER = 'security.authentication_listener.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_FACTORY = 'security.authentication_listener.factory.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ENTRY_POINT = 'security.entry_point.';

    /**
     * @var string
     */
    protected const PROTO = '._proto';

    /**
     * @var array<string>
     */
    protected const ENTRY_POINTS = [
        'http',
        'form',
    ];

    /**
     * @var array
     */
    protected const DEFAULT_AUTHENTICATION_LISTENER_FACTORY_TYPES = [
        'logout',
        'pre_auth',
        'form',
        'http',
        'user_session_validator',
        'user_session_handler',
    ];

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        foreach (static::DEFAULT_AUTHENTICATION_LISTENER_FACTORY_TYPES as $type) {
            $entryPoint = $this->getEntryPoint($type);

            $container->set(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_FACTORY . $type, $container->protect(function (string $firewallName, array $options) use ($type, $container, $entryPoint): array {
                $entryForFirewall = static::SERVICE_SECURITY_ENTRY_POINT . $firewallName . '.' . $entryPoint;
                $authListenerForFirewall = static::SERVICE_SECURITY_AUTHENTICATION_LISTENER . $firewallName . '.' . $type;

                if (!$container->has($authListenerForFirewall)) {
                    $authListenerPrototype = static::SERVICE_SECURITY_AUTHENTICATION_LISTENER . $type . static::PROTO;

                    $container->set(
                        $authListenerForFirewall,
                        $container->get($authListenerPrototype)($firewallName, $options),
                    );
                }

                if ($entryPoint && !$container->has($entryForFirewall)) {
                    $entryPointPrototype = static::SERVICE_SECURITY_ENTRY_POINT . $entryPoint . static::PROTO;

                    $container->set(
                        $entryForFirewall,
                        $container->get($entryPointPrototype)($firewallName, $options),
                    );
                }

                return [
                    $authListenerForFirewall,
                    $entryPoint ? $entryForFirewall : null,
                    $type,
                ];
            }));
        }

        return $container;
    }

    /**
     * @param string $type
     *
     * @return string|null
     */
    protected function getEntryPoint(string $type): ?string
    {
        if (in_array($type, static::ENTRY_POINTS)) {
            return $type;
        }

        return null;
    }
}
