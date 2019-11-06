<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Plugin\Application;

use LogicException;
use Psr\Log\LoggerInterface;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Security\Configuration\SecurityConfiguration;
use Spryker\Yves\Security\Configuration\SecurityConfigurationInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver;
use Symfony\Component\Security\Core\Authentication\Provider\AnonymousAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPasswordValidator;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Guard\Firewall\GuardAuthenticationListener;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Guard\Provider\GuardAuthenticationProvider;
use Symfony\Component\Security\Http\AccessMap;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\EntryPoint\BasicAuthenticationEntryPoint;
use Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint;
use Symfony\Component\Security\Http\EntryPoint\RetryAuthenticationEntryPoint;
use Symfony\Component\Security\Http\Firewall;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\Firewall\AccessListener;
use Symfony\Component\Security\Http\Firewall\AnonymousAuthenticationListener;
use Symfony\Component\Security\Http\Firewall\BasicAuthenticationListener;
use Symfony\Component\Security\Http\Firewall\ChannelListener;
use Symfony\Component\Security\Http\Firewall\ContextListener;
use Symfony\Component\Security\Http\Firewall\ExceptionListener;
use Symfony\Component\Security\Http\Firewall\LogoutListener;
use Symfony\Component\Security\Http\Firewall\SwitchUserListener;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener;
use Symfony\Component\Security\Http\FirewallMap;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;
use Symfony\Component\Security\Http\Logout\SessionLogoutHandler;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

/**
 * @method \Spryker\Yves\Security\SecurityFactory getFactory()
 * @method \Spryker\Yves\Security\SecurityConfig getConfig()()
 */
class SecurityApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface, BootableApplicationPluginInterface
{
    protected const SERVICE_SECURITY_FIREWALL = 'security.firewall';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     */
    protected const SERVICE_ROUTER = 'routers';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_KERNEL
     */
    protected const SERVICE_KERNEL = 'kernel';

    /**
     * @uses \Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     */
    protected const SERVICE_DISPATCHER = 'dispatcher';
    protected const SERVICE_SECURITY_FIREWALLS = 'security.firewalls';
    protected const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * Used to register routes for login_check and logout.
     *
     * @var array
     */
    protected $securityRoutes = [];

    /**
     * @var \Spryker\Yves\Security\Configuration\SecurityConfigurationInterface|null
     */
    protected $securityConfiguration;

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addAuthorizationChecker($container);
        $container = $this->addTokenStorage($container);
        $container = $this->addUser($container);
        $container = $this->addAuthenticationManager($container);
        $container = $this->addEncoder($container);
        $container = $this->addUserChecker($container);
        $container = $this->addAccessManager($container);
        $container = $this->addVoters($container);
        $container = $this->addFirewall($container);
        $container = $this->addChannelListener($container);
        $container = $this->addAuthenticationListenerFactories($container);
        $container = $this->addAccessListener($container);
        $container = $this->addAccessMap($container);
        $container = $this->addTrustResolver($container);
        $container = $this->addUtils($container);
        $container = $this->addLastError($container);
        $container = $this->addUserProviderPrototypes($container);
        $container = $this->addListenerPrototypes($container);
        $container = $this->addAuthenticationHandlerPrototypes($container);
        $container = $this->addAuthenticationListenerPrototypes($container);
        $container = $this->addEntryPointPrototypes($container);
        $container = $this->addAuthenticationProviderPrototypes($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityConfigurationInterface
     */
    protected function getSecurityConfiguration(ContainerInterface $container): SecurityConfigurationInterface
    {
        if ($this->securityConfiguration === null) {
            $securityConfiguration = new SecurityConfiguration();
            foreach ($this->getFactory()->getSecurityPlugins() as $securityPlugin) {
                $securityConfiguration = $securityPlugin->extend($securityConfiguration, $container);
            }

            $this->securityConfiguration = $securityConfiguration;
        }

        return $this->securityConfiguration;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthorizationChecker(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER, function (ContainerInterface $container) {
            return new AuthorizationChecker(
                $container->get('security.token_storage'),
                $container->get('security.authentication_manager'),
                $container->get('security.access_manager')
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addTokenStorage(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.token_storage', function () {
            return new TokenStorage();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addUser(ContainerInterface $container): ContainerInterface
    {
        $container->set('user', $container->factory(function (ContainerInterface $container) {
            $token = $container->get('security.token_storage')->getToken();
            if ($token === null) {
                return null;
            }
            if (!is_object($user = $token->getUser())) {
                return null;
            }

            return $user;
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationManager(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_manager', function (ContainerInterface $container) {
            $manager = new AuthenticationProviderManager($container->get('security.authentication_providers'));
            $manager->setEventDispatcher($container->get(static::SERVICE_DISPATCHER));

            return $manager;
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addEncoder(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.encoder_factory', function () {
            return new EncoderFactory([
                UserInterface::class => $this->getFactory()->createPasswordEncoder(),
            ]);
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addUserChecker(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.user_checker', function () {
            return new UserChecker();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAccessManager(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.access_manager', function (ContainerInterface $container) {
            return new AccessDecisionManager($container->get('security.voters'));
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addVoters(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.voters', function (ContainerInterface $container) {
            $securityConfiguration = $this->getSecurityConfiguration($container);

            return [
                new RoleHierarchyVoter(new RoleHierarchy($securityConfiguration->getRoleHierarchies())),
                new AuthenticatedVoter($container->get('security.trust_resolver')),
            ];
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addFirewall(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.firewall', function (ContainerInterface $container) {
            if ($container->has('validator')) {
                $container->set('security.validator.user_password_validator', function (ContainerInterface $container) {
                    return new UserPasswordValidator($container->get('security.token_storage'), $container->get('security.encoder_factory'));
                });
                $container->set('validator.validator_service_ids', array_merge(
                    $container->get('validator.validator_service_ids'),
                    ['security.validator.user_password' => 'security.validator.user_password_validator']
                ));
            }

            return new Firewall($this->getFirewallMap($container), $container->get(static::SERVICE_DISPATCHER));
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @throws \LogicException
     *
     * @return \Symfony\Component\Security\Http\FirewallMapInterface
     */
    protected function getFirewallMap(ContainerInterface $container): FirewallMapInterface
    {
        $positions = ['logout', 'pre_auth', 'guard', 'form', 'http', 'remember_me', 'anonymous'];
        $providers = [];
        $configs = [];

        foreach ($this->getSecurityConfiguration($container)->getFirewalls() as $firewallName => $firewallConfiguration) {
            $entryPoint = null;
            $pattern = $firewallConfiguration['pattern'] ?? null;
            $users = $firewallConfiguration['users'] ?? [];
            $security = (bool)($firewallConfiguration['security'] ?? true);
            $stateless = (bool)($firewallConfiguration['stateless'] ?? false);
            $context = $firewallConfiguration['context'] ?? $firewallName;
            $hosts = $firewallConfiguration['hosts'] ?? null;
            $methods = $firewallConfiguration['methods'] ?? null;
            unset($firewallConfiguration['pattern'], $firewallConfiguration['users'], $firewallConfiguration['security'], $firewallConfiguration['stateless'], $firewallConfiguration['context'], $firewallConfiguration['methods'], $firewallConfiguration['hosts']);
            $protected = $security === false ? false : count($firewallConfiguration);
            $listeners = ['security.channel_listener'];

            if (is_string($users)) {
                $users = function () use ($container, $users) {
                    return $container->get($users);
                };
            }

            if ($protected) {
                if (!$container->has('security.user_provider.' . $firewallName)) {
                    $container->set('security.user_provider.' . $firewallName, is_array($users) ? $container->get('security.user_provider.inmemory._proto')($users) : $users);
                }

                if (!$container->has('security.context_listener.' . $context)) {
                    $container->set('security.context_listener.' . $context, $container->get('security.context_listener._proto')($firewallName, [$container->get('security.user_provider.' . $firewallName)]));
                }

                if ($stateless === false) {
                    $listeners[] = 'security.context_listener.' . $context;
                }

                $factories = [];
                foreach ($positions as $position) {
                    $factories[$position] = [];
                }

                foreach ($firewallConfiguration as $type => $options) {
                    if ($type === 'switch_user') {
                        continue;
                    }
                    // normalize options
                    if (!is_array($options)) {
                        if (!$options) {
                            continue;
                        }
                        $options = [];
                    }
                    if (!$container->has('security.authentication_listener.factory.' . $type)) {
                        throw new LogicException(sprintf('The "%s" authentication entry is not registered.', $type));
                    }
                    $options['stateless'] = $stateless;
                    [$providerId, $listenerId, $entryPointId, $position] = $container->get('security.authentication_listener.factory.' . $type)($firewallName, $options);
                    if ($entryPointId !== null) {
                        $entryPoint = $entryPointId;
                    }
                    $factories[$position][] = $listenerId;
                    $providers[] = $providerId;
                }

                foreach ($positions as $position) {
                    foreach ($factories[$position] as $listener) {
                        $listeners[] = $listener;
                    }
                }

                $listeners[] = 'security.access_listener';
                if (isset($firewallConfiguration['switch_user'])) {
                    $switchUserConfiguration = (array)$firewallConfiguration['switch_user'];
                    $switchUserConfiguration['stateless'] = $stateless;
                    $container->set('security.switch_user.' . $firewallName, $container->get('security.authentication_listener.switch_user._proto')($firewallName, $switchUserConfiguration));
                    $listeners[] = 'security.switch_user.' . $firewallName;
                }

                if (!$container->has('security.exception_listener.' . $firewallName)) {
                    if ($entryPoint === null) {
                        $entryPoint = 'security.entry_point.' . $firewallName . '.form';
                        $container->set($entryPoint, $container->get('security.entry_point.form._proto')($firewallName, []));
                    }
                    $accessDeniedHandler = null;
                    if ($container->has('security.access_denied_handler.' . $firewallName)) {
                        $accessDeniedHandler = $container->get('security.access_denied_handler.' . $firewallName);
                    }

                    $securityConfiguration = $this->getSecurityConfiguration($container);
                    if (isset($securityConfiguration->getAccessDeniedHandler()[$firewallName])) {
                        $accessDeniedHandler = call_user_func($securityConfiguration->getAccessDeniedHandler()[$firewallName], $container);
                    }

                    $container->set('security.exception_listener.' . $firewallName, $container->get('security.exception_listener._proto')($entryPoint, $firewallName, $accessDeniedHandler));
                }
            }
            $configs[$firewallName] = [
                'pattern' => $pattern,
                'listeners' => $listeners,
                'protected' => $protected,
                'methods' => $methods,
                'hosts' => $hosts,
            ];
        }

        $securityAuthenticationProviders = array_map(function ($provider) use ($container) {
            return $container->get($provider);
        }, array_unique($providers));

        $container->set('security.authentication_providers', $securityAuthenticationProviders);

        return $this->buildFirewallMap($container, $configs);
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
        foreach ($configs as $name => $config) {
            $requestMatcher = $config['pattern'];
            if (is_string($config['pattern'])) {
                $requestMatcher = new RequestMatcher($config['pattern'], $config['hosts'], $config['methods']);
            }

            $firewallMap->add(
                $requestMatcher,
                array_map(function ($listenerId) use ($container, $name) {
                    $listener = $container->get($listenerId);
                    if ($container->has('security.remember_me.service.' . $name)) {
                        if ($listener instanceof AbstractAuthenticationListener || $listener instanceof GuardAuthenticationListener) {
                            $listener->setRememberMeServices($container->get('security.remember_me.service.' . $name));
                        }
                        if ($listener instanceof LogoutListener) {
                            $listener->addHandler($container->get('security.remember_me.service.' . $name));
                        }
                    }

                    return $listener;
                }, $config['listeners']),
                $config['protected'] ? $container->get('security.exception_listener.' . $name) : null
            );
        }

        return $firewallMap;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addChannelListener(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.channel_listener', function (ContainerInterface $container) {
            return new ChannelListener(
                $container->get('security.access_map'),
                new RetryAuthenticationEntryPoint(
                    $container->has('request.http_port') ? $container->get('request.http_port') : 80,
                    $container->has('request.https_port') ? $container->get('request.https_port') : 443
                ),
                $this->getLogger($container)
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerFactories(ContainerInterface $container): ContainerInterface
    {
        foreach (['logout', 'pre_auth', 'guard', 'form', 'http', 'remember_me', 'anonymous'] as $type) {
            $entryPoint = null;

            if ($type === 'http') {
                $entryPoint = 'http';
            } elseif ($type === 'form') {
                $entryPoint = 'form';
            } elseif ($type === 'guard') {
                $entryPoint = 'guard';
            }

            $container->set('security.authentication_listener.factory.' . $type, $container->protect(function ($name, $options) use ($type, $container, $entryPoint) {
                if ($entryPoint && !$container->has('security.entry_point.' . $name . '.' . $entryPoint)) {
                    $container->set('security.entry_point.' . $name . '.' . $entryPoint, $container->get('security.entry_point.' . $entryPoint . '._proto')($name, $options));
                }
                if (!$container->has('security.authentication_listener.' . $name . '.' . $type)) {
                    $container->set('security.authentication_listener.' . $name . '.' . $type, $container->get('security.authentication_listener.' . $type . '._proto')($name, $options));
                }
                $provider = 'dao';

                if ($type === 'anonymous') {
                    $provider = 'anonymous';
                } elseif ($type === 'guard') {
                    $provider = 'guard';
                }

                if (!$container->has('security.authentication_provider.' . $name . '.' . $provider)) {
                    $container->set('security.authentication_provider.' . $name . '.' . $provider, $container->get('security.authentication_provider.' . $provider . '._proto')($name, $options));
                }

                return [
                    'security.authentication_provider.' . $name . '.' . $provider,
                    'security.authentication_listener.' . $name . '.' . $type,
                    $entryPoint ? 'security.entry_point.' . $name . '.' . $entryPoint : null,
                    $type,
                ];
            }));
        }

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAccessListener(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.access_listener', function (ContainerInterface $container) {
            return new AccessListener(
                $container->get('security.token_storage'),
                $container->get('security.access_manager'),
                $container->get('security.access_map'),
                $container->get('security.authentication_manager')
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAccessMap(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.access_map', function (ContainerInterface $container) {
            $map = new AccessMap();
            $accessRules = $this->getSecurityConfiguration($container)->getAccessRules();
            foreach ($accessRules as $rule) {
                if (is_string($rule[0])) {
                    $rule[0] = new RequestMatcher($rule[0]);
                } elseif (is_array($rule[0])) {
                    $rule[0] += [
                        'path' => null,
                        'host' => null,
                        'methods' => null,
                        'ips' => null,
                        'attributes' => [],
                        'schemes' => null,
                    ];
                    $rule[0] = new RequestMatcher($rule[0]['path'], $rule[0]['host'], $rule[0]['methods'], $rule[0]['ips'], $rule[0]['attributes'], $rule[0]['schemes']);
                }
                $map->add($rule[0], (array)$rule[1], isset($rule[2]) ? $rule[2] : null);
            }

            return $map;
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addTrustResolver(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.trust_resolver', function () {
            return new AuthenticationTrustResolver(AnonymousToken::class, RememberMeToken::class);
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addUtils(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.http_utils', function (ContainerInterface $container) {
            $chainRouter = $container->get(static::SERVICE_ROUTER);

            return new HttpUtils($chainRouter, $chainRouter);
        });

        $container->set('security.authentication_utils', function (ContainerInterface $container) {
            return new AuthenticationUtils($container->get('request_stack'));
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addLastError(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.last_error', $container->protect(function (Request $request) {
            if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
                return $request->attributes->get(Security::AUTHENTICATION_ERROR)->getMessage();
            }

            $session = $request->getSession();

            if ($session && $session->has(Security::AUTHENTICATION_ERROR)) {
                $message = $session->get(Security::AUTHENTICATION_ERROR)->getMessage();
                $session->remove(Security::AUTHENTICATION_ERROR);

                return $message;
            }
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addUserProviderPrototypes(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.user_provider.inmemory._proto', $container->protect(function ($params) {
            return function () use ($params) {
                $users = [];
                foreach ($params as $name => $user) {
                    $users[$name] = ['roles' => (array)$user[0], 'password' => $user[1]];
                }

                return new InMemoryUserProvider($users);
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addListenerPrototypes(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.context_listener._proto', $container->protect(function ($providerKey, $userProviders) use ($container) {
            return function () use ($container, $userProviders, $providerKey) {
                return new ContextListener(
                    $container->get('security.token_storage'),
                    $userProviders,
                    $providerKey,
                    $this->getLogger($container),
                    $container->get(static::SERVICE_DISPATCHER)
                );
            };
        }));

        $container->set('security.exception_listener._proto', $container->protect(function ($entryPoint, $name, $accessDeniedHandler = null) use ($container) {
            return function () use ($container, $entryPoint, $name, $accessDeniedHandler) {
                return new ExceptionListener(
                    $container->get('security.token_storage'),
                    $container->get('security.trust_resolver'),
                    $container->get('security.http_utils'),
                    $name,
                    $container->get($entryPoint),
                    null, // errorPage
                    $accessDeniedHandler,
                    $this->getLogger($container)
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
    protected function addAuthenticationHandlerPrototypes(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addAuthenticationSuccessHandlerPrototype($container);
        $container = $this->addAuthenticationFailureHandlerPrototype($container);
        $container = $this->addAuthenticationLogoutHandlerPrototype($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationSuccessHandlerPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication.success_handler._proto', $container->protect(function ($name, $options) use ($container) {
            return function () use ($name, $options, $container) {
                $handler = new DefaultAuthenticationSuccessHandler(
                    $container->get('security.http_utils'),
                    $options
                );
                $handler->setProviderKey($name);

                return $handler;
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationFailureHandlerPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication.failure_handler._proto', $container->protect(function ($name, $options) use ($container) {
            return function () use ($options, $container) {
                return new DefaultAuthenticationFailureHandler(
                    $container->get(static::SERVICE_KERNEL),
                    $container->get('security.http_utils'),
                    $options,
                    $this->getLogger($container)
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
    protected function addAuthenticationLogoutHandlerPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication.logout_handler._proto', $container->protect(function ($name, $options) use ($container) {
            return function () use ($options, $container) {
                return new DefaultLogoutSuccessHandler(
                    $container->get('security.http_utils'),
                    $options['target_url'] ?? '/'
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
    protected function addAuthenticationListenerPrototypes(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addAuthenticationListenerGuardPrototype($container);
        $container = $this->addAuthenticationListenerFormPrototype($container);
        $container = $this->addAuthenticationListenerHttpPrototype($container);
        $container = $this->addAuthenticationListenerAnonymousPrototype($container);
        $container = $this->addAuthenticationListenerSwitchUserPrototype($container);
        $container = $this->addAuthenticationListenerLogoutPrototype($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerGuardPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_listener.guard._proto', $container->protect(function ($providerKey, $options) use ($container) {
            return function () use ($container, $providerKey, $options) {
                if (!$container->has('security.authentication.guard_handler')) {
                    $container->set('security.authentication.guard_handler', new GuardAuthenticatorHandler($container->get('security.token_storage'), $container->get(static::SERVICE_DISPATCHER)));
                }
                $authenticators = [];
                foreach ($options['authenticators'] as $authenticatorId) {
                    $authenticators[] = $container->get($authenticatorId);
                }

                return new GuardAuthenticationListener(
                    $container->get('security.authentication.guard_handler'),
                    $container->get('security.authentication_manager'),
                    $providerKey,
                    $authenticators,
                    $this->getLogger($container)
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
    protected function addAuthenticationListenerFormPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_listener.form._proto', $container->protect(function ($name, $options) use ($container) {
            return function () use ($container, $name, $options) {
                $tmp = $options['check_path'] ?? '/login_check';
                $this->addFakeRoute('match', $tmp, str_replace('/', '_', ltrim($tmp, '/')));

                $class = $options['listener_class'] ?? UsernamePasswordFormAuthenticationListener::class;

                return new $class(
                    $container->get('security.token_storage'),
                    $container->get('security.authentication_manager'),
                    $this->getSessionStrategy($container, $name),
                    $container->get('security.http_utils'),
                    $name,
                    $this->getAuthenticationSuccessHandler($container, $name, $options),
                    $this->getAuthenticationFailureHandler($container, $name, $options),
                    $options,
                    $this->getLogger($container),
                    $container->get(static::SERVICE_DISPATCHER),
                    $this->getCsrfTokenManager($container, $options)
                );
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param string $name
     *
     * @return \Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface
     */
    protected function getSessionStrategy(ContainerInterface $container, string $name): SessionAuthenticationStrategyInterface
    {
        return $container->has('security.session_strategy.' . $name) ? $container->get('security.session_strategy.' . $name) : $this->getFactory()->createSessionStrategy();
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Psr\Log\LoggerInterface|null
     */
    protected function getLogger(ContainerInterface $container): ?LoggerInterface
    {
        return $container->has('logger') ? $container->get('logger') : null;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param string $firewallName
     * @param array $options
     *
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface
     */
    protected function getAuthenticationSuccessHandler(ContainerInterface $container, string $firewallName, array $options): AuthenticationSuccessHandlerInterface
    {
        $securityConfiguration = $this->getSecurityConfiguration($container);
        if (isset($securityConfiguration->getAuthenticationSuccessHandler()[$firewallName])) {
            return call_user_func($securityConfiguration->getAuthenticationSuccessHandler()[$firewallName], $container, $options);
        }

        if (!$container->has('security.authentication.success_handler.' . $firewallName)) {
            $container->set('security.authentication.success_handler.' . $firewallName, $container->get('security.authentication.success_handler._proto')($firewallName, $options));
        }

        return $container->get('security.authentication.success_handler.' . $firewallName);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param string $firewallName
     * @param array $options
     *
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface
     */
    protected function getAuthenticationFailureHandler(ContainerInterface $container, string $firewallName, array $options): AuthenticationFailureHandlerInterface
    {
        $securityConfiguration = $this->getSecurityConfiguration($container);
        if (isset($securityConfiguration->getAuthenticationFailureHandler()[$firewallName])) {
            return call_user_func($securityConfiguration->getAuthenticationFailureHandler()[$firewallName], $container, $options);
        }

        if (!$container->has('security.authentication.failure_handler.' . $firewallName)) {
            $container->set('security.authentication.failure_handler.' . $firewallName, $container->get('security.authentication.failure_handler._proto')($firewallName, $options));
        }

        return $container->get('security.authentication.failure_handler.' . $firewallName);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerHttpPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_listener.http._proto', $container->protect(function ($providerKey, $options) use ($container) {
            return function () use ($container, $providerKey) {
                return new BasicAuthenticationListener(
                    $container->get('security.token_storage'),
                    $container->get('security.authentication_manager'),
                    $providerKey,
                    $container->get('security.entry_point.' . $providerKey . '.http'),
                    $this->getLogger($container)
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
    protected function addAuthenticationListenerAnonymousPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_listener.anonymous._proto', $container->protect(function ($providerKey, $options) use ($container) {
            return function () use ($container, $providerKey) {
                return new AnonymousAuthenticationListener(
                    $container->get('security.token_storage'),
                    $providerKey,
                    $this->getLogger($container)
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
        $container->set('security.authentication_listener.logout._proto', $container->protect(function ($name, $options) use ($container) {
            return function () use ($container, $name, $options) {
                $tmp = $options['logout_path'] ?? '/logout';
                $this->addFakeRoute('get', $tmp, str_replace('/', '_', ltrim($tmp, '/')));

                if (!isset($container['security.authentication.logout_handler.' . $name])) {
                    $container->set('security.authentication.logout_handler.' . $name, $container->get('security.authentication.logout_handler._proto')($name, $options));
                }

                $listener = new LogoutListener(
                    $container->get('security.token_storage'),
                    $container->get('security.http_utils'),
                    $container->get('security.authentication.logout_handler.' . $name),
                    $options,
                    $this->getCsrfTokenManager($container, $options)
                );

                $listener = $this->addSessionLogoutHandler($listener, $options);

                return $listener;
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param array $options
     *
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManager|null
     */
    protected function getCsrfTokenManager(ContainerInterface $container, array $options): ?CsrfTokenManager
    {
        return isset($options['with_csrf']) && $options['with_csrf'] && $container->has('csrf.token_manager') ? $container->get('csrf.token_manager') : null;
    }

    /**
     * @param \Symfony\Component\Security\Http\Firewall\LogoutListener $listener
     * @param array $options
     *
     * @return \Symfony\Component\Security\Http\Firewall\LogoutListener
     */
    protected function addSessionLogoutHandler(LogoutListener $listener, array $options): LogoutListener
    {
        $invalidateSession = $options['invalidate_session'] ?? true;

        if ($invalidateSession === true && $options['stateless'] === false) {
            $listener->addHandler(new SessionLogoutHandler());
        }

        return $listener;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationListenerSwitchUserPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_listener.switch_user._proto', $container->protect(function ($name, $options) use ($container) {
            return function () use ($container, $name, $options) {
                return new SwitchUserListener(
                    $container->get('security.token_storage'),
                    $container->get('security.user_provider.' . $name),
                    $container->get('security.user_checker'),
                    $name,
                    $container->get('security.access_manager'),
                    $this->getLogger($container),
                    isset($options['parameter']) ? $options['parameter'] : '_switch_user',
                    isset($options['role']) ? $options['role'] : 'ROLE_ALLOWED_TO_SWITCH',
                    $container->get(static::SERVICE_DISPATCHER),
                    $options['stateless'] ?? true
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
    protected function addEntryPointPrototypes(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addEntryPointFormPrototype($container);
        $container = $this->addEntryPointHttpPrototype($container);
        $container = $this->addEntryPointGuardPrototype($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addEntryPointFormPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.entry_point.form._proto', $container->protect(function ($name, array $options) use ($container) {
            return function () use ($container, $options) {
                $loginPath = isset($options['login_path']) ? $options['login_path'] : '/login';
                $useForward = isset($options['use_forward']) ? $options['use_forward'] : false;

                return new FormAuthenticationEntryPoint($container->get(static::SERVICE_KERNEL), $container->get('security.http_utils'), $loginPath, $useForward);
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addEntryPointHttpPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.entry_point.http._proto', $container->protect(function ($name, array $options) {
            return function () use ($options) {
                return new BasicAuthenticationEntryPoint($options['real_name'] ?? 'Secured');
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @throws \LogicException
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addEntryPointGuardPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.entry_point.guard._proto', $container->protect(function ($name, array $options) use ($container) {
            if (isset($options['entry_point'])) {
                return $container->get($options['entry_point']);
            }

            $authenticatorIds = $options['authenticators'];

            if (count($authenticatorIds) === 1) {
                return $container->get(reset($authenticatorIds));
            }

            throw new LogicException(sprintf(
                'Because you have multiple guard configurators, you need to set the "guard.entry_point" key to one of your configurators (%s)',
                implode(', ', $authenticatorIds)
            ));
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationProviderPrototypes(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addAuthenticationProviderDaoPrototype($container);
        $container = $this->addAuthenticationProviderGuardPrototype($container);
        $container = $this->addAuthenticationProviderAnonymousPrototype($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addAuthenticationProviderDaoPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_provider.dao._proto', $container->protect(function ($name, $options) use ($container) {
            return function () use ($container, $name) {
                return new DaoAuthenticationProvider(
                    $container->get('security.user_provider.' . $name),
                    $container->get('security.user_checker'),
                    $name,
                    $container->get('security.encoder_factory'),
                    $this->getConfig()->hideUserNotFoundException()
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
    protected function addAuthenticationProviderGuardPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_provider.guard._proto', $container->protect(function ($name, $options) use ($container) {
            return function () use ($container, $name, $options) {
                $authenticators = [];
                foreach ($options['authenticators'] as $authenticatorId) {
                    $authenticators[] = $container->get($authenticatorId);
                }

                return new GuardAuthenticationProvider(
                    $authenticators,
                    $container->get('security.user_provider.' . $name),
                    $name,
                    $container->get('security.user_checker')
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
    protected function addAuthenticationProviderAnonymousPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set('security.authentication_provider.anonymous._proto', $container->protect(function ($name, $options) {
            return function () use ($name) {
                return new AnonymousAuthenticationProvider($name);
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface
    {
        $this->addSubscriber($container);
        $this->addRouter($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    protected function addSubscriber(ContainerInterface $container): void
    {
        $dispatcher = $this->getDispatcher($container);
        $dispatcher->addSubscriber($container->get(static::SERVICE_SECURITY_FIREWALL));

        foreach ($this->getSecurityConfiguration($container)->getEventSubscriber() as $eventSubscriber) {
            $dispatcher->addSubscriber(call_user_func($eventSubscriber, $container));
        }
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function getDispatcher(ContainerInterface $container): EventDispatcherInterface
    {
        return $container->get(static::SERVICE_DISPATCHER);
    }

    /**
     * @param string $method
     * @param string $pattern
     * @param string $name
     *
     * @return void
     */
    protected function addFakeRoute(string $method, string $pattern, string $name)
    {
        $this->securityRoutes[] = [$method, $pattern, $name];
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @throws \LogicException
     *
     * @return void
     */
    protected function addRouter(ContainerInterface $container): void
    {
        $loader = new ClosureLoader();
        $fakeRoutes = $this->securityRoutes;

        $resource = function () use ($fakeRoutes) {
            $routeCollection = new RouteCollection();
            foreach ($fakeRoutes as $route) {
                [$method, $pattern, $name] = $route;

                $route = new Route($pattern);

                $controller = function (Request $request) {
                    throw new LogicException(sprintf('The "%s" route must have code to run when it matches.', $request->attributes->get('_route')));
                };

                $route->setDefault('_controller', $controller);
//                $route->setMethods([$method]);

                $routeCollection->add($name, $route);
            }

            return $routeCollection;
        };

        $router = new Router($loader, $resource, []);
        $container->get(static::SERVICE_ROUTER)->add($router, 1);
    }
}
