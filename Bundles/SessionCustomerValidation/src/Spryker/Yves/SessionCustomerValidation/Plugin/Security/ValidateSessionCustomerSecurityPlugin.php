<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionCustomerValidation\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @deprecated Use {@link \SprykerShop\Yves\SessionCustomerValidationPage\Plugin\Security\ValidateCustomerSessionSecurityPlugin} instead.
 *
 * @method \Spryker\Yves\SessionCustomerValidation\SessionCustomerValidationFactory getFactory()
 */
class ValidateSessionCustomerSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_TOKEN_STORAGE
     *
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @uses \SprykerShop\Shared\CustomerPage\CustomerPageConfig::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'secured';

    /**
     * @var string
     */
    protected const SECURITY_CUSTOMER_SESSION_VALIDATOR = 'security.authentication_listener.customer_session_validator';

    /**
     * {@inheritDoc}
     * - Extends security service customer session validator listener.
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
        $this->extendCustomerFirewall($securityBuilder);
        $this->addAuthenticationListenerFactory($container);
        $this->addAuthenticationListenerPrototype($container);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function extendCustomerFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $customerFirewallConfiguration = $this->findFirewall(static::SECURITY_FIREWALL_NAME, $securityBuilder);

        if ($customerFirewallConfiguration === null) {
            return $securityBuilder;
        }

        $securityBuilder->mergeFirewall(static::SECURITY_FIREWALL_NAME, [
            'customer_session_validator' => static::SECURITY_CUSTOMER_SESSION_VALIDATOR,
            'context' => $customerFirewallConfiguration['context'],
        ]);

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
            'security.authentication_listener.factory.customer_session_validator',
            $container->protect(
                function ($firewallName, $options) use ($container) {
                    $listenerName = sprintf('security.authentication_listener.%s.customer_session_validator', $firewallName);
                    if (!$container->has($listenerName)) {
                        $container->set(
                            $listenerName,
                            $container->get('security.authentication_listener.customer_session_validator._proto')($firewallName),
                        );
                    }

                    return [
                        'security.authentication_provider.' . $firewallName . '.anonymous',
                        $listenerName,
                        null,
                        'customer_session_validator',
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
        $container->set('security.authentication_listener.customer_session_validator._proto', $container->protect(function ($providerKey) use ($container) {
            return function () use ($container) {
                return $this->getFactory()->createValidateSessionCustomerListener($container->get(static::SERVICE_SECURITY_TOKEN_STORAGE));
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
