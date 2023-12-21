<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Security\AuthenticationListener\AuthenticationListenerInterface;

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
     * @var \Spryker\Yves\Security\AuthenticationListener\AuthenticationListenerInterface
     */
    protected AuthenticationListenerInterface $authenticationListener;

    /**
     * @param \Spryker\Yves\Security\AuthenticationListener\AuthenticationListenerInterface $authenticationListener
     */
    public function __construct(AuthenticationListenerInterface $authenticationListener)
    {
        $this->authenticationListener = $authenticationListener;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        foreach ($this->authenticationListener->getAuthenticationListenerFactoryTypes() as $type) {
            $entryPoint = $this->getEntryPoint($type);

            $container->set(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_FACTORY . $type, $container->protect(function (string $firewallName, array $options) use ($type, $container, $entryPoint): array {
                if ($entryPoint && !$container->has(static::SERVICE_SECURITY_ENTRY_POINT . $firewallName . '.' . $entryPoint)) {
                    $container->set(
                        static::SERVICE_SECURITY_ENTRY_POINT . $firewallName . '.' . $entryPoint,
                        $container->get(static::SERVICE_SECURITY_ENTRY_POINT . $entryPoint . static::PROTO)($firewallName, $options),
                    );
                }

                if (!$container->has(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER . $firewallName . '.' . $type)) {
                    $container->set(
                        static::SERVICE_SECURITY_AUTHENTICATION_LISTENER . $firewallName . '.' . $type,
                        $container->get(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER . $type . static::PROTO)($firewallName, $options),
                    );
                }

                return [
                    static::SERVICE_SECURITY_AUTHENTICATION_LISTENER . $firewallName . '.' . $type,
                    $entryPoint ? static::SERVICE_SECURITY_ENTRY_POINT . $firewallName . '.' . $entryPoint : null,
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
