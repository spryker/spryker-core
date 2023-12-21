<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Security\EventListener\RedirectLogoutListener;
use Spryker\Zed\Security\Communication\Configurator\SecurityConfiguratorInterface;
use Spryker\Zed\Security\Communication\Loader\AuthenticatorManager\AuthenticatorManagerInterface;
use Spryker\Zed\Security\Communication\Router\SecurityRouterInterface;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\EventListener\CheckCredentialsListener;
use Symfony\Component\Security\Http\EventListener\SessionLogoutListener;
use Symfony\Component\Security\Http\Firewall\AuthenticatorManagerListener;
use Symfony\Component\Security\Http\Firewall\FirewallListenerInterface;
use Symfony\Component\Security\Http\Firewall\LogoutListener;
use Symfony\Component\Security\Http\Firewall\SwitchUserListener;

class AuthenticationListenerPrototypesServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ACCESS_MANAGER = 'security.access_manager';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_HASHER_FACTORY = 'security.hasher_factory';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_USER_CHECKER = 'security.user_checker';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_HTTP_UTILS = 'security.http_utils';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_USER_PROVIDER = 'security.user_provider.';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_FORM_PROTO = 'security.authentication_listener.form._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_HTTP_PROTO = 'security.authentication_listener.http._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_LOGOUT_PROTO = 'security.authentication_listener.logout._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_VALIDATOR_PROTO = 'security.authentication_listener.user_session_validator._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_HANDLER_PROTO = 'security.authentication_listener.user_session_handler._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_SWITCH_USER_PROTO = 'security.authentication_listener.switch_user._proto';

    /**
     * @var string
     */
    protected const SECURITY_ENTRY_POINT_HTTP_AUTHENTICATOR = 'security.entry_point.http-auth.http';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     *
     * @var string
     */
    protected const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @uses \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     *
     * @var string
     */
    protected const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * @var string
     */
    protected const SERVICE_LOGGER = 'logger';

    /**
     * @var string
     */
    protected const OPTION_LISTENER_CLASS = 'listener_class';

    /**
     * @var string
     */
    protected const OPTION_CHECK_PATH = 'check_path';

    /**
     * @var string
     */
    protected const OPTION_LOGOUT_PATH = 'logout_path';

    /**
     * @var string
     */
    protected const OPTION_ROLE = 'role';

    /**
     * @var string
     */
    protected const OPTION_PARAMETER = 'parameter';

    /**
     * @var string
     */
    protected const OPTION_PATTERN = 'pattern';

    /**
     * @var string
     */
    protected const OPTION_PRIORITY = 'priority';

    /**
     * @var string
     */
    protected const OPTION_WITH_CSRF = 'with_csrf';

    /**
     * @var string
     */
    protected const OPTION_METHODS = 'methods';

    /**
     * @var string
     */
    protected const OPTION_STATELESS = 'stateless';

    /**
     * @var string
     */
    protected const OPTION_HOSTS = 'hosts';

    /**
     * @var string
     */
    protected const OPTION_TARGET_URL = 'target_url';

    /**
     * @var string
     */
    protected const ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    /**
     * @var string
     */
    protected const PARAMETER_SWITCH_USER = '_switch_user';

    /**
     * @var string
     */
    protected const URI_LOGIN_CHECK = '/login_check';

    /**
     * @var string
     */
    protected const URI_LOGOUT = '/logout';

    /**
     * @var string
     */
    protected const INDEX_URL = '/';

    /**
     * @var string
     */
    protected const KEY_AUTHENTICATORS = 'authenticators';

    /**
     * @var int
     */
    protected const DEFAULT_PRIORITY = 64;

    /**
     * @var \Spryker\Zed\Security\Communication\Configurator\SecurityConfiguratorInterface
     */
    protected SecurityConfiguratorInterface $securityConfigurator;

    /**
     * @var \Spryker\Zed\Security\Communication\Router\SecurityRouterInterface
     */
    protected SecurityRouterInterface $securityRouter;

    /**
     * @var \Spryker\Zed\Security\Communication\Loader\AuthenticatorManager\AuthenticatorManagerInterface
     */
    protected AuthenticatorManagerInterface $authenticatorManager;

    /**
     * @param \Spryker\Zed\Security\Communication\Configurator\SecurityConfiguratorInterface $securityConfigurator
     * @param \Spryker\Zed\Security\Communication\Router\SecurityRouterInterface $securityRouter
     * @param \Spryker\Zed\Security\Communication\Loader\AuthenticatorManager\AuthenticatorManagerInterface $authenticatorManager
     */
    public function __construct(
        SecurityConfiguratorInterface $securityConfigurator,
        SecurityRouterInterface $securityRouter,
        AuthenticatorManagerInterface $authenticatorManager
    ) {
        $this->securityConfigurator = $securityConfigurator;
        $this->securityRouter = $securityRouter;
        $this->authenticatorManager = $authenticatorManager;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addAuthenticationListenerFormPrototype($container);
        $container = $this->addAuthenticationListenerHttpPrototype($container);
        $container = $this->addAuthenticationListenerSwitchUserPrototype($container);
        $container = $this->addAuthenticationListenerLogoutPrototype($container);
        $container = $this->addUserSessionValidatorListenerPrototype($container);
        $container = $this->addUserSessionHandlerListenerPrototype($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerFormPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_FORM_PROTO, $container->protect(function (string $firewallName, array $options) use ($container): callable {
            return function () use ($container, $firewallName, $options): FirewallListenerInterface {
                $this->securityRouter->addSecurityRoute($options[static::OPTION_CHECK_PATH] ?? static::URI_LOGIN_CHECK);

                $container->get(static::SERVICE_DISPATCHER)->addSubscriber(
                    new CheckCredentialsListener($container->get(static::SERVICE_SECURITY_HASHER_FACTORY)),
                );

                $authManager = $this->authenticatorManager->create($container, $firewallName, $options);

                /** @var \Symfony\Component\Security\Http\Firewall\FirewallListenerInterface $class */
                $class = $options[static::OPTION_LISTENER_CLASS] ?? AuthenticatorManagerListener::class;

                return new $class(
                    $authManager,
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
    protected function addAuthenticationListenerHttpPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_HTTP_PROTO, $container->protect(function (string $firewallName, array $options) use ($container): callable {
            return function () use ($container, $firewallName, $options): FirewallListenerInterface {
                $container->get(static::SERVICE_DISPATCHER)->addSubscriber(
                    new CheckCredentialsListener($container->get(static::SERVICE_SECURITY_HASHER_FACTORY)),
                );

                $options = [static::KEY_AUTHENTICATORS => [static::SECURITY_ENTRY_POINT_HTTP_AUTHENTICATOR]];

                return new AuthenticatorManagerListener(
                    $this->authenticatorManager->create($container, $firewallName, $options),
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
    protected function addAuthenticationListenerSwitchUserPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_SWITCH_USER_PROTO, $container->protect(function (string $firewallName, array $options) use ($container): callable {
            return function () use ($container, $firewallName, $options): FirewallListenerInterface {
                return new SwitchUserListener(
                    $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
                    $container->get(static::SERVICE_SECURITY_USER_PROVIDER . $firewallName),
                    $container->get(static::SERVICE_SECURITY_USER_CHECKER),
                    $firewallName,
                    $container->get(static::SERVICE_SECURITY_ACCESS_MANAGER),
                    $container->has(static::SERVICE_LOGGER) ? $container->get(static::SERVICE_LOGGER) : null,
                    $options[static::OPTION_PARAMETER] ?? static::PARAMETER_SWITCH_USER,
                    $options[static::OPTION_ROLE] ?? static::ROLE_ALLOWED_TO_SWITCH,
                    $container->get(static::SERVICE_DISPATCHER),
                    $options[static::OPTION_STATELESS] ?? true,
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
    protected function addAuthenticationListenerLogoutPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_LOGOUT_PROTO, $container->protect(function (string $firewallName, array $options) use ($container): callable {
            return function () use ($container, $firewallName, $options): FirewallListenerInterface {
                $this->securityRouter->addSecurityRoute($options[static::OPTION_LOGOUT_PATH] ?? static::URI_LOGOUT);

                /** @var \Symfony\Contracts\EventDispatcher\EventDispatcherInterface|\Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher */
                $eventDispatcher = $container->get(static::SERVICE_DISPATCHER);
                $httpUtils = $container->get(static::SERVICE_SECURITY_HTTP_UTILS);
                $requestMatcher = $this->createRequestMatcher($container, $firewallName);

                $container->get(static::SERVICE_DISPATCHER)->addSubscriber(new RedirectLogoutListener(
                    $httpUtils,
                    $requestMatcher,
                    $options[static::OPTION_TARGET_URL] ?? static::INDEX_URL,
                    $options[static::OPTION_PRIORITY] ?? static::DEFAULT_PRIORITY,
                ));

                $container->get(static::SERVICE_DISPATCHER)->addSubscriber(new SessionLogoutListener());

                $listener = new LogoutListener(
                    $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
                    $container->get(static::SERVICE_SECURITY_HTTP_UTILS),
                    $eventDispatcher,
                    $options,
                    $this->getCsrfTokenManager($container, $options),
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
    protected function addUserSessionValidatorListenerPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(
            static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_VALIDATOR_PROTO,
            $container->protect(function (string $firewallName, array $options): ?FirewallListenerInterface {
                return null;
            }),
        );

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addUserSessionHandlerListenerPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(
            static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_HANDLER_PROTO,
            $container->protect(function (string $firewallName, array $options): ?FirewallListenerInterface {
                return null;
            }),
        );

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param string $firewallName
     *
     * @return \Symfony\Component\HttpFoundation\RequestMatcher|mixed
     */
    protected function createRequestMatcher(ContainerInterface $container, string $firewallName)
    {
        $config = $this->securityConfigurator->getSecurityConfiguration($container)->getFirewalls()[$firewallName] ?? null;
        $requestMatcher = $config[static::OPTION_PATTERN];

        if (is_string($requestMatcher)) {
            $requestMatcher = new RequestMatcher(
                $config[static::OPTION_PATTERN],
                $config[static::OPTION_HOSTS] ?? null,
                $config[static::OPTION_METHODS] ?? null,
            );
        }

        return $requestMatcher;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface|null
     */
    protected function getCsrfTokenManager(ContainerInterface $container, array $options): ?CsrfTokenManagerInterface
    {
        return !empty($options[static::OPTION_WITH_CSRF]) && $container->has(static::SERVICE_FORM_CSRF_PROVIDER) ? $container->get(static::SERVICE_FORM_CSRF_PROVIDER) : null;
    }
}
