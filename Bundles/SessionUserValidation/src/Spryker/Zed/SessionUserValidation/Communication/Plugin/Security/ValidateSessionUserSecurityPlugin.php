<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidation\Communication\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SessionUserValidation\Communication\SessionUserValidationCommunicationFactory getFactory()
 * @method \Spryker\Zed\SessionUserValidation\SessionUserValidationConfig getConfig()
 */
class ValidateSessionUserSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_TOKEN_STORAGE
     *
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
    protected const SECURITY_USER_SESSION_VALIDATOR = 'security.authentication_listener.user_session_validator';

    /**
     * {@inheritDoc}
     * - Extends security service user session validator listener.
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
                'user_session_validator' => [
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
            'security.authentication_listener.factory.user_session_validator',
            $container->protect(
                function ($firewallName, $options) use ($container) {
                    $listenerName = sprintf('security.authentication_listener.%s.user_session_validator', $firewallName);
                    if (!$container->has($listenerName)) {
                        $container->set(
                            $listenerName,
                            $container->get('security.authentication_listener.user_session_validator._proto')($firewallName),
                        );
                    }

                    return [
                        'security.authentication_provider.' . $firewallName . '.anonymous',
                        $listenerName,
                        null,
                        'user_session_validator',
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
        $container->set('security.authentication_listener.user_session_validator._proto', $container->protect(function ($providerKey) use ($container) {
            return function () use ($container) {
                return $this->getFactory()->createValidateSessionUserListener($container->get(static::SERVICE_SECURITY_TOKEN_STORAGE));
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
