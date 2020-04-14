<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Plugin\Security;

use Exception;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Symfony\Component\Security\Core\Authentication\Provider\RememberMeAuthenticationProvider;
use Symfony\Component\Security\Http\Firewall\RememberMeListener;
use Symfony\Component\Security\Http\RememberMe\ResponseListener;
use Symfony\Component\Security\Http\RememberMe\TokenBasedRememberMeServices;

class RememberMeSecurityPlugin implements SecurityPluginInterface
{
    protected const LIFETIME_ONE_YEAR = 31536000;

    /**
     * @uses \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     */
    protected const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * {@inheritDoc}
     * - Adds remember_me related services.
     * - Adds a ResponseListener.
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
        $this->addPrototypes($container);

        $securityBuilder->addEventSubscriber(function () {
            return new ResponseListener();
        });

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerFactory(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_listener.factory.remember_me', $container->protect(function ($name, $options) use ($container) {
            if (empty($options['key'])) {
                $options['key'] = $name;
            }

            if (!$container->has('security.remember_me.service.' . $name)) {
                $container->set('security.remember_me.service.' . $name, $container->get('security.remember_me.service._proto')($name, $options));
            }

            if (!$container->has('security.authentication_listener.' . $name . '.remember_me')) {
                $container->set('security.authentication_listener.' . $name . '.remember_me', $container->get('security.authentication_listener.remember_me._proto')($name));
            }

            if (!$container->has('security.authentication_provider.' . $name . '.remember_me')) {
                $container->set('security.authentication_provider.' . $name . '.remember_me', $container->get('security.authentication_provider.remember_me._proto')($name, $options));
            }

            return [
                'security.authentication_provider.' . $name . '.remember_me',
                'security.authentication_listener.' . $name . '.remember_me',
                null, // entry point
                'remember_me',
            ];
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addPrototypes(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addRememberMeServicePrototype($container);
        $container = $this->addAuthenticationListenerPrototype($container);
        $container = $this->addAuthenticationProviderPrototype($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @throws \Exception
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addRememberMeServicePrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.remember_me.service._proto', $container->protect(function ($providerKey, $options) use ($container) {
            return function () use ($providerKey, $options, $container) {
                $options = array_replace([
                    'name' => 'REMEMBERME',
                    'lifetime' => static::LIFETIME_ONE_YEAR,
                    'path' => '/',
                    'domain' => null,
                    'secure' => false,
                    'httponly' => true,
                    'always_remember_me' => false,
                    'remember_me_parameter' => '_remember_me',
                ], $options);

                if (!is_array($options)) {
                    throw new Exception('An error occured "array_replace" returned "null".');
                }

                $logger = $container->has('logger') ? $container->get('logger') : null;

                return new TokenBasedRememberMeServices([$container->get('security.user_provider.' . $providerKey)], $options['key'], $providerKey, $options, $logger);
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_listener.remember_me._proto', $container->protect(function ($providerKey) use ($container) {
            return function () use ($container, $providerKey) {
                $listener = new RememberMeListener(
                    $container->get('security.token_storage'),
                    $container->get('security.remember_me.service.' . $providerKey),
                    $container->get('security.authentication_manager'),
                    $container->has('logger') ? $container->get('logger') : null,
                    $container->get(static::SERVICE_DISPATCHER)
                );

                return $listener;
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationProviderPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_provider.remember_me._proto', $container->protect(function ($name, $options) use ($container) {
            return function () use ($container, $name, $options) {
                return new RememberMeAuthenticationProvider($container->get('security.user_checker'), $options['key'], $name);
            };
        }));

        return $container;
    }
}
