<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Security\Exception\AuthenticationEntryNotRegisteredException;
use Spryker\Yves\Security\AuthenticationListener\AuthenticationListenerInterface;
use Spryker\Yves\Security\Configurator\SecurityConfiguratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Security\Http\Firewall;
use Symfony\Component\Security\Http\FirewallMap;
use Symfony\Component\Security\Http\FirewallMapInterface;

class FirewallServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_FIREWALL = 'security.firewall';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_CHANNEL_LISTENER = 'security.channel_listener';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ACCESS_LISTENER = 'security.access_listener';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_EXCEPTION_LISTENER = 'security.exception_listener.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ACCESS_DENIED_HANDLER = 'security.access_denied_handler.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_SWITCH_USER = 'security.switch_user.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_CONTEXT_LISTENER = 'security.context_listener.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ENTRY_POINT = 'security.entry_point.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_USER_PROVIDER = 'security.user_provider.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_FACTORY = 'security.authentication_listener.factory.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_EXCEPTION_LISTENER_PROTO = 'security.exception_listener._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ENTRY_POINT_FORM_PROTO = 'security.entry_point.form._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_CONTEXT_LISTENER_PROTO = 'security.context_listener._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_SWITCH_USER_PROTO = 'security.authentication_listener.switch_user._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_USER_PROVIDER_INMEMORY_PROTO = 'security.user_provider.inmemory._proto';

    /**
     * @uses \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     *
     * @var string
     */
    protected const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * @var string
     */
    protected const KEY_SWITCH_USER = 'switch_user';

    /**
     * @var string
     */
    protected const FORM = '.form';

    /**
     * @var string
     */
    protected const OPTION_SECURITY = 'security';

    /**
     * @var string
     */
    protected const OPTION_LISTENERS = 'listeners';

    /**
     * @var string
     */
    protected const OPTION_PROTECTED = 'protected';

    /**
     * @var string
     */
    protected const OPTION_USERS = 'users';

    /**
     * @var string
     */
    protected const OPTION_CONTEXT = 'context';

    /**
     * @var string
     */
    protected const OPTION_HOSTS = 'hosts';

    /**
     * @var string
     */
    protected const OPTION_PATTERN = 'pattern';

    /**
     * @var string
     */
    protected const OPTION_STATELESS = 'stateless';

    /**
     * @var string
     */
    protected const OPTION_METHODS = 'methods';

    /**
     * @var array<string>
     */
    protected const SKIPPED_AUTHENTICATION_TYPES = [
        'switch_user',
        'remember_me',
    ];

    /**
     * @var string
     */
    protected const EXCEPTION_AUTHENTICATION_ENTRY_NOT_REGISTERED_MESSAGE = 'Authentication entry `%s` is not registered.';

    /**
     * @var \Spryker\Yves\Security\Configurator\SecurityConfiguratorInterface
     */
    protected SecurityConfiguratorInterface $securityConfigurator;

    /**
     * @var \Spryker\Yves\Security\AuthenticationListener\AuthenticationListenerInterface
     */
    protected AuthenticationListenerInterface $authenticationListener;

    /**
     * @param \Spryker\Yves\Security\Configurator\SecurityConfiguratorInterface $securityConfigurator
     * @param \Spryker\Yves\Security\AuthenticationListener\AuthenticationListenerInterface $authenticationListener
     */
    public function __construct(
        SecurityConfiguratorInterface $securityConfigurator,
        AuthenticationListenerInterface $authenticationListener
    ) {
        $this->securityConfigurator = $securityConfigurator;
        $this->authenticationListener = $authenticationListener;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_FIREWALL, function (ContainerInterface $container): EventSubscriberInterface {
            return new Firewall(
                $this->getFirewallMap($container),
                $container->get(static::SERVICE_DISPATCHER),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @throws \Spryker\Shared\Security\Exception\AuthenticationEntryNotRegisteredException
     *
     * @return \Symfony\Component\Security\Http\FirewallMapInterface
     */
    protected function getFirewallMap(ContainerInterface $container): FirewallMapInterface
    {
        $configs = [];
        $firewalls = $this->securityConfigurator->getSecurityConfiguration($container)->getFirewalls();

        foreach ($firewalls as $firewallName => $firewallConfiguration) {
            $entryPoint = '';
            $configurations = $this->getConfigurations($firewallConfiguration, $firewallName);
            unset(
                $firewallConfiguration[static::OPTION_PATTERN],
                $firewallConfiguration[static::OPTION_USERS],
                $firewallConfiguration[static::OPTION_SECURITY],
                $firewallConfiguration[static::OPTION_STATELESS],
                $firewallConfiguration[static::OPTION_CONTEXT],
                $firewallConfiguration[static::OPTION_METHODS],
                $firewallConfiguration[static::OPTION_HOSTS],
            );
            $listeners = [static::SERVICE_SECURITY_CHANNEL_LISTENER];

            if ($configurations[static::OPTION_SECURITY] === false) {
                $configs = $this->mapConfigs($configs, $configurations, $listeners, $firewallConfiguration, $firewallName);

                continue;
            }

            $container = $this->extendContainerWithUserServiceProvider($configurations, $container, $firewallName);
            $container = $this->extendContainerWithContextListener($configurations, $container, $firewallName);

            if ($configurations[static::OPTION_STATELESS] === false) {
                $listeners[] = static::SERVICE_SECURITY_CONTEXT_LISTENER . $configurations[static::OPTION_CONTEXT];
            }

            $factories = array_fill_keys($this->authenticationListener->getAuthenticationListenerFactoryTypes(), []);

            foreach ($firewallConfiguration as $type => $options) {
                if (in_array($type, static::SKIPPED_AUTHENTICATION_TYPES)) {
                    continue;
                }

                if (!$container->has(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_FACTORY . $type)) {
                    throw new AuthenticationEntryNotRegisteredException(
                        sprintf(static::EXCEPTION_AUTHENTICATION_ENTRY_NOT_REGISTERED_MESSAGE, $type),
                    );
                }

                [$listenerId, $entryPointId, $authenticationListenerFactoryType] = $container->get(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_FACTORY . $type)($firewallName, $this->normalizeOptions($options, $configurations));

                $entryPoint = $entryPointId ?? $entryPoint;
                $factories[$authenticationListenerFactoryType][] = $listenerId;
            }

            $listeners = $this->collectListeners($factories, $listeners);

            if (isset($firewallConfiguration[static::KEY_SWITCH_USER])) {
                $container = $this->setSwitchUserListener($container, $firewallConfiguration, $firewallName);
                $listeners[] = static::SERVICE_SECURITY_SWITCH_USER . $firewallName;
            }

            $container = $this->setFirewallExceptionListener($firewallName, $entryPoint, $container);
            $configs = $this->mapConfigs($configs, $configurations, $listeners, $firewallConfiguration, $firewallName);
        }

        return $this->buildFirewallMap($container, $configs);
    }

    /**
     * @param array<mixed> $firewallConfiguration
     * @param string $firewallName
     *
     * @return array<mixed>
     */
    protected function getConfigurations(array $firewallConfiguration, string $firewallName): array
    {
        return [
            static::OPTION_PATTERN => $firewallConfiguration[static::OPTION_PATTERN] ?? null,
            static::OPTION_USERS => $firewallConfiguration[static::OPTION_USERS] ?? [],
            static::OPTION_SECURITY => (bool)($firewallConfiguration[static::OPTION_SECURITY] ?? true),
            static::OPTION_STATELESS => (bool)($firewallConfiguration[static::OPTION_STATELESS] ?? false),
            static::OPTION_CONTEXT => $firewallConfiguration[static::OPTION_CONTEXT] ?? $firewallName,
            static::OPTION_HOSTS => $firewallConfiguration[static::OPTION_HOSTS] ?? null,
            static::OPTION_METHODS => $firewallConfiguration[static::OPTION_METHODS] ?? null,
        ];
    }

    /**
     * @param array<mixed> $configs
     * @param array<mixed> $configurations
     * @param array<mixed> $listeners
     * @param array<mixed> $firewallConfiguration
     * @param string $firewallName
     *
     * @return array<mixed>
     */
    protected function mapConfigs(
        array $configs,
        array $configurations,
        array $listeners,
        array $firewallConfiguration,
        string $firewallName
    ): array {
        $configs[$firewallName] = [
            static::OPTION_PATTERN => $configurations[static::OPTION_PATTERN],
            static::OPTION_LISTENERS => $listeners,
            static::OPTION_PROTECTED => $configurations[static::OPTION_SECURITY] === false ? false : count($firewallConfiguration),
            static::OPTION_METHODS => $configurations[static::OPTION_METHODS],
            static::OPTION_HOSTS => $configurations[static::OPTION_HOSTS],
        ];

        return $configs;
    }

    /**
     * @param array<mixed> $configurations
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param string $firewallName
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function extendContainerWithUserServiceProvider(
        array $configurations,
        ContainerInterface $container,
        string $firewallName
    ): ContainerInterface {
        if ($container->has(static::SERVICE_SECURITY_USER_PROVIDER . $firewallName)) {
            return $container;
        }

        $users = $configurations[static::OPTION_USERS];

        if (is_string($users)) {
            $users = function () use ($container, $configurations): array {
                return $container->get($configurations[static::OPTION_USERS]);
            };
        }

        $container->set(
            static::SERVICE_SECURITY_USER_PROVIDER . $firewallName,
            is_array($users) ? $container->get(static::SERVICE_SECURITY_USER_PROVIDER_INMEMORY_PROTO)($users) : $users,
        );

        return $container;
    }

    /**
     * @param array<mixed> $configurations
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param string $firewallName
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function extendContainerWithContextListener(
        array $configurations,
        ContainerInterface $container,
        string $firewallName
    ): ContainerInterface {
        if ($container->has(static::SERVICE_SECURITY_CONTEXT_LISTENER . $configurations[static::OPTION_CONTEXT])) {
            return $container;
        }

        $container->set(
            static::SERVICE_SECURITY_CONTEXT_LISTENER . $configurations[static::OPTION_CONTEXT],
            $container->get(static::SERVICE_SECURITY_CONTEXT_LISTENER_PROTO)($firewallName, [$container->get(static::SERVICE_SECURITY_USER_PROVIDER . $firewallName)]),
        );

        return $container;
    }

    /**
     * @param mixed $options
     * @param array<mixed> $configurations
     *
     * @return array<mixed>
     */
    protected function normalizeOptions($options, array $configurations): array
    {
        $options = is_array($options) ? $options : [];
        $options[static::OPTION_STATELESS] = $configurations[static::OPTION_STATELESS];

        return $options;
    }

    /**
     * @param array<mixed> $factories
     * @param array<mixed> $listeners
     *
     * @return array<mixed>
     */
    protected function collectListeners(
        array $factories,
        array $listeners
    ): array {
        foreach ($this->authenticationListener->getAuthenticationListenerFactoryTypes() as $authenticationListenerFactoryType) {
            $listeners = [...$listeners, ...$factories[$authenticationListenerFactoryType]];
        }

        $listeners[] = static::SERVICE_SECURITY_ACCESS_LISTENER;

        return $listeners;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param array<mixed> $firewallConfiguration
     * @param string $firewallName
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function setSwitchUserListener(
        ContainerInterface $container,
        array $firewallConfiguration,
        string $firewallName
    ): ContainerInterface {
        $switchUserConfiguration = (array)$firewallConfiguration[static::KEY_SWITCH_USER];
        $switchUserConfiguration[static::OPTION_STATELESS] = (bool)($firewallConfiguration[static::OPTION_STATELESS] ?? false);

        $container->set(
            static::SERVICE_SECURITY_SWITCH_USER . $firewallName,
            $container->get(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_SWITCH_USER_PROTO)($firewallName, $switchUserConfiguration),
        );

        return $container;
    }

    /**
     * @param string $firewallName
     * @param string|null $entryPoint
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function setFirewallExceptionListener(
        string $firewallName,
        ?string $entryPoint,
        ContainerInterface $container
    ): ContainerInterface {
        if ($container->has(static::SERVICE_SECURITY_EXCEPTION_LISTENER . $firewallName)) {
            return $container;
        }

        if ($entryPoint === null) {
            $container->set(
                static::SERVICE_SECURITY_ENTRY_POINT . $firewallName . static::FORM,
                $container->get(static::SERVICE_SECURITY_ENTRY_POINT_FORM_PROTO)($firewallName, []),
            );
        }

        $container->set(
            static::SERVICE_SECURITY_EXCEPTION_LISTENER . $firewallName,
            $container->get(static::SERVICE_SECURITY_EXCEPTION_LISTENER_PROTO)($entryPoint, $firewallName),
        );

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param array $configs
     *
     * @return \Symfony\Component\Security\Http\FirewallMapInterface
     */
    protected function buildFirewallMap(ContainerInterface $container, array $configs): FirewallMapInterface
    {
        $firewallMap = new FirewallMap();

        foreach ($configs as $firewallName => $config) {
            $requestMatcher = $config[static::OPTION_PATTERN];

            if (is_string($config[static::OPTION_PATTERN])) {
                $requestMatcher = new RequestMatcher($config[static::OPTION_PATTERN], $config[static::OPTION_HOSTS], $config[static::OPTION_METHODS]);
            }

            $firewallMap->add(
                $requestMatcher,
                $this->mapListeners($container, $config[static::OPTION_LISTENERS]),
                $config[static::OPTION_PROTECTED] ? $container->get(static::SERVICE_SECURITY_EXCEPTION_LISTENER . $firewallName) : null,
            );
        }

        return $firewallMap;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param array $listeners
     *
     * @return array
     */
    protected function mapListeners(ContainerInterface $container, array $listeners): array
    {
        $mappedListeners = array_map(function (string $listenerId) use ($container) {
            return $container->get($listenerId);
        }, $listeners);

        return array_filter($mappedListeners);
    }
}
