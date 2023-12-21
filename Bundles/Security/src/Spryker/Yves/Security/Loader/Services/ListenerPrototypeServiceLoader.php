<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Http\Firewall\ContextListener;
use Symfony\Component\Security\Http\Firewall\ExceptionListener;
use Symfony\Component\Security\Http\Firewall\FirewallListenerInterface;

class ListenerPrototypeServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_HTTP_UTILS = 'security.http_utils';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TRUST_RESOLVER = 'security.trust_resolver';

    /**
     * @var string
     */
    protected const SERVICE_LOGGER = 'logger';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_EXCEPTION_LISTENER_PROTO = 'security.exception_listener._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_CONTEXT_LISTENER_PROTO = 'security.context_listener._proto';

    /**
     * @uses \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     *
     * @var string
     */
    protected const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addServiceSecurityContextListenerProto($container);
        $container = $this->addServiceSecurityExceptionListenerProto($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addServiceSecurityContextListenerProto(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_CONTEXT_LISTENER_PROTO, $container->protect(function (string $firewallName, array $userProviders) use ($container): callable {
            return function () use ($container, $userProviders, $firewallName): FirewallListenerInterface {
                return new ContextListener(
                    $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
                    $userProviders,
                    $firewallName,
                    $container->has(static::SERVICE_LOGGER) ? $container->get(static::SERVICE_LOGGER) : null,
                    $container->get(static::SERVICE_DISPATCHER),
                );
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addServiceSecurityExceptionListenerProto(ContainerInterface $container): ContainerInterface
    {
        $container->set(
            static::SERVICE_SECURITY_EXCEPTION_LISTENER_PROTO,
            $container->protect(function (string $entryPoint, string $firewallName, ?AccessDeniedHandlerInterface $accessDeniedHandler = null) use ($container): callable {
                return function () use ($container, $entryPoint, $firewallName, $accessDeniedHandler): ExceptionListener {
                    return new ExceptionListener(
                        $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
                        $container->get(static::SERVICE_SECURITY_TRUST_RESOLVER),
                        $container->get(static::SERVICE_SECURITY_HTTP_UTILS),
                        $firewallName,
                        $container->get($entryPoint),
                        null,
                        $accessDeniedHandler,
                        $container->has(static::SERVICE_LOGGER) ? $container->get(static::SERVICE_LOGGER) : null,
                    );
                };
            }),
        );

        return $container;
    }
}
