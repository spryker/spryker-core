<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\User\Communication\Plugin\Security\Listener\CurrentUserSessionHandlerListener;

/**
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\User\UserConfig getConfig()
 */
class UserSessionHandlerSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_TOKEN_STORAGE
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * {@inheritDoc}
     * - Extends security service user session handler listener.
     *
     * @api
     *
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
            'security.authentication_listener.factory.user_session_handler',
            $container->protect(
                function ($firewallName, $options) use ($container) {
                    $listenerName = sprintf('security.authentication_listener.%s.user_session_handler', $firewallName);
                    if (!$container->has($listenerName)) {
                        $container->set(
                            $listenerName,
                            $container->get('security.authentication_listener.user_session_handler._proto')($firewallName)
                        );
                    }

                    return [
                        'security.authentication_provider.' . $firewallName . '.anonymous',
                        $listenerName,
                        null,
                        'user_session_handler',
                    ];
                }
            )
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
        $container->set('security.authentication_listener.user_session_handler._proto', $container->protect(function ($providerKey) use ($container) {
            return function () use ($container) {
                return new CurrentUserSessionHandlerListener(
                    $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
                    $this->getFacade()
                );
            };
        }));

        return $container;
    }
}
