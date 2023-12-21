<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidation\Communication\Extender;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Zed\SessionUserValidation\Communication\FirewallListener\ValidateSessionUserListener;
use Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface;
use Symfony\Component\Security\Http\Firewall\FirewallListenerInterface;

class SecurityServiceExtender implements SecurityServiceExtenderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Plugin\Security\UserSecurityPlugin::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_USER_FIREWALL_NAME = 'User';

    /**
     * @var string
     */
    protected const SECURITY_AUTHENTICATION_LISTENER_FACTORY_USER_SESSION_VALIDATOR = 'security.authentication_listener.factory.user_session_validator';

    /**
     * @var string
     */
    protected const SECURITY_USER_SESSION_VALIDATOR = 'security.authentication_listener.user_session_validator';

    /**
     * @var string
     */
    protected const SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_VALIDATOR_PLACEHOLDER = 'security.authentication_listener.%s.user_session_validator';

    /**
     * @var string
     */
    protected const SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_VALIDATOR_PROTO = 'security.authentication_listener.user_session_validator._proto';

    /**
     * @var string
     */
    protected const USER_SESSION_VALIDATOR = 'user_session_validator';

    /**
     * @var \Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface
     */
    protected SessionUserValidationToUserFacadeInterface $userFacade;

    /**
     * @var \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface
     */
    protected SessionUserValidatorPluginInterface $sessionUserValidatorPlugin;

    /**
     * @param \Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface $sessionUserValidatorPlugin
     */
    public function __construct(
        SessionUserValidationToUserFacadeInterface $userFacade,
        SessionUserValidatorPluginInterface $sessionUserValidatorPlugin
    ) {
        $this->userFacade = $userFacade;
        $this->sessionUserValidatorPlugin = $sessionUserValidatorPlugin;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $this->extendUserFirewall($securityBuilder);
        $this->addAuthenticationListenerFactory($container);
        $this->addAuthenticationListenerPrototype($container);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function extendUserFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $userFirewallConfiguration = $this->findFirewall(static::SECURITY_USER_FIREWALL_NAME, $securityBuilder);

        if ($userFirewallConfiguration === null) {
            return $securityBuilder;
        }

        $securityBuilder->addFirewall(static::SECURITY_USER_FIREWALL_NAME, [
                static::USER_SESSION_VALIDATOR => [
                    static::SECURITY_USER_SESSION_VALIDATOR,
                ],
            ] + $userFirewallConfiguration);

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
            static::SECURITY_AUTHENTICATION_LISTENER_FACTORY_USER_SESSION_VALIDATOR,
            $container->protect(
                function (string $firewallName, array $options) use ($container): array {
                    $listenerName = sprintf(static::SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_VALIDATOR_PLACEHOLDER, $firewallName);

                    if (!$container->has($listenerName)) {
                        $container->set(
                            $listenerName,
                            $container->get(static::SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_VALIDATOR_PROTO)($firewallName),
                        );
                    }

                    return [
                        $listenerName,
                        null,
                        static::USER_SESSION_VALIDATOR,
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
        $container->set(static::SECURITY_AUTHENTICATION_LISTENER_USER_SESSION_VALIDATOR_PROTO, $container->protect(function (string $firewallName) use ($container): callable {
            return function () use ($container): FirewallListenerInterface {
                return new ValidateSessionUserListener(
                    $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE),
                    $this->userFacade,
                    $this->sessionUserValidatorPlugin,
                );
            };
        }));

        return $container;
    }

    /**
     * @param string $firewallName
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return array<mixed>|null
     */
    protected function findFirewall(string $firewallName, SecurityBuilderInterface $securityBuilder): ?array
    {
        $firewalls = (clone $securityBuilder)->getConfiguration()->getFirewalls();

        return $firewalls[$firewallName] ?? null;
    }
}
