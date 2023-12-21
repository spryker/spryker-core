<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Extender;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Zed\User\Business\UserFacadeInterface;
use Spryker\Zed\User\Communication\Plugin\Security\Listener\CurrentUserSessionHandlerListener;
use Symfony\Component\Security\Http\Firewall\FirewallListenerInterface;

class SecurityServiceExtender implements SecurityServiceExtenderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var string
     */
    protected const SECURITY_AUTHENTICATION_LISTENER_FACTORY_USER_SESSION_HANDLER = 'security.authentication_listener.factory.user_session_handler';

    /**
     * @var string
     */
    protected const SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_HANDLER_PLACEHOLDER = 'security.authentication_listener.%s.user_session_handler';

    /**
     * @var string
     */
    protected const SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_HANDLER_PROTO = 'security.authentication_listener.user_session_handler._proto';

    /**
     * @var string
     */
    protected const USER_SESSION_HANDLER = 'user_session_handler';

    /**
     * @var \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected UserFacadeInterface $userFacade;

    /**
     * @param \Spryker\Zed\User\Business\UserFacadeInterface $userFacade
     */
    public function __construct(UserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $this->addAuthenticationListenerFactory($container);
        $this->addAuthenticationListenerPrototype($container);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerFactory(ContainerInterface $container): ContainerInterface
    {
        $container->set(
            static::SECURITY_AUTHENTICATION_LISTENER_FACTORY_USER_SESSION_HANDLER,
            $container->protect(
                function (string $firewallName, array $options) use ($container): array {
                    $listenerName = sprintf(static::SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_HANDLER_PLACEHOLDER, $firewallName);

                    if (!$container->has($listenerName)) {
                        $container->set(
                            $listenerName,
                            $container->get(static::SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_HANDLER_PROTO)($firewallName),
                        );
                    }

                    return [
                        $listenerName,
                        null,
                        static::USER_SESSION_HANDLER,
                    ];
                },
            ),
        );

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_HANDLER_PROTO, $container->protect(function (string $firewallName) use ($container): callable {
            return function () use ($container): FirewallListenerInterface {
                return new CurrentUserSessionHandlerListener(
                    $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
                    $this->userFacade,
                );
            };
        }));

        return $container;
    }
}
