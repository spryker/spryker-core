<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Communication\Expander;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class SecurityBuilderExpander implements SecurityBuilderExpanderInterface
{
    /**
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'system_user';

    /**
     * @var string
     */
    protected const SECURITY_SYSTEM_USER_TOKEN_AUTHENTICATOR = 'security.system_user.token.authenticator';

    /**
     * @var string
     */
    protected const GATEWAY_PATTERN = '^/(.+)/gateway/';

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected UserProviderInterface $userProvider;

    /**
     * @var \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    protected AuthenticatorInterface $authenticator;

    /**
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface $authenticator
     */
    public function __construct(
        UserProviderInterface $userProvider,
        AuthenticatorInterface $authenticator
    ) {
        $this->userProvider = $userProvider;
        $this->authenticator = $authenticator;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $securityBuilder = $this->addFirewall($securityBuilder);
        $securityBuilder = $this->addAccessRules($securityBuilder);
        $this->addAuthenticator($container);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addFirewall(static::SECURITY_FIREWALL_NAME, [
            'pattern' => static::GATEWAY_PATTERN,
            'form' => [
                'authenticators' => [
                    static::SECURITY_SYSTEM_USER_TOKEN_AUTHENTICATOR,
                ],
            ],
            'users' => function () {
                return $this->userProvider;
            },
        ]);
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addAccessRules(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addAccessRules([
            [
                static::GATEWAY_PATTERN,
                SecuritySystemUserConfig::ROLE_SYSTEM_USER,
            ],
        ]);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    protected function addAuthenticator(ContainerInterface $container): void
    {
        $container->set(static::SECURITY_SYSTEM_USER_TOKEN_AUTHENTICATOR, function () {
            return $this->authenticator;
        });
    }
}
