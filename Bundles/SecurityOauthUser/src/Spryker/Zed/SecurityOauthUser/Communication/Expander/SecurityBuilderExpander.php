<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Expander;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;
use Symfony\Component\Security\Core\User\ChainUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class SecurityBuilderExpander implements SecurityBuilderExpanderInterface
{
    /**
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'OauthUser';

    /**
     * @var string
     */
    protected const SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR = 'security.OauthUser.token.authenticator';

    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Expander\SecurityBuilderExpander::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_USER_FIREWALL_NAME = 'User';

    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Expander\SecurityBuilderExpander::SECURITY_USER_LOGIN_FORM_AUTHENTICATOR
     *
     * @var string
     */
    protected const SECURITY_USER_LOGIN_FORM_AUTHENTICATOR = 'security.User.login_form.authenticator';

    /**
     * @var string
     */
    protected const USERS = 'users';

    /**
     * @var string
     */
    protected const ACCESS_MODE_PUBLIC = 'PUBLIC_ACCESS';

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected UserProviderInterface $userProvider;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig
     */
    protected SecurityOauthUserConfig $config;

    /**
     * @var \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface
     */
    protected AuthenticatorInterface $authenticator;

    /**
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig $config
     * @param \Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface $authenticator
     */
    public function __construct(
        UserProviderInterface $userProvider,
        SecurityOauthUserConfig $config,
        AuthenticatorInterface $authenticator
    ) {
        $this->userProvider = $userProvider;
        $this->config = $config;
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
        $securityBuilder = $this->addFirewalls($securityBuilder);
        $securityBuilder = $this->addAccessRules($securityBuilder);
        $this->addAuthenticator($container);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewalls(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder = $this->expandUserFirewall($securityBuilder);

        if ($this->findFirewall(static::SECURITY_USER_FIREWALL_NAME, $securityBuilder) === null) {
            $securityBuilder = $this->addOauthUserFirewall($securityBuilder);
        }

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function expandUserFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $userFirewallConfiguration = $this->findFirewall(static::SECURITY_USER_FIREWALL_NAME, $securityBuilder);

        if ($userFirewallConfiguration === null) {
            return $securityBuilder;
        }

        $securityBuilder->addFirewall(static::SECURITY_USER_FIREWALL_NAME, [
                'form' => [
                    'authenticators' => [
                        static::SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR,
                        static::SECURITY_USER_LOGIN_FORM_AUTHENTICATOR,
                    ],
                ],
                'users' => function () use ($userFirewallConfiguration) {
                    return new ChainUserProvider([
                        $userFirewallConfiguration[static::USERS](),
                        $this->userProvider,
                    ]);
                },
            ] + $userFirewallConfiguration);

        return $securityBuilder;
    }

    /**
     * @param string $firewallName
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return array|null
     */
    protected function findFirewall(string $firewallName, SecurityBuilderInterface $securityBuilder): ?array
    {
        $firewalls = (clone $securityBuilder)->getConfiguration()->getFirewalls();

        return $firewalls[$firewallName] ?? null;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addOauthUserFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addFirewall(static::SECURITY_FIREWALL_NAME, [
            'pattern' => $this->config->getBackOfficeRoutePattern(),
            'form' => [
                'authenticators' => [
                    static::SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR,
                ],
            ],
            'logout' => [
                'login_path' => $this->config->getUrlLogin(),
                'logout_path' => $this->config->getUrlLogout(),
            ],
            'users' => function () {
                return $this->userProvider;
            },
            'user_session_handler' => true,
        ]);

        return $securityBuilder;
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
                $this->config->getIgnorablePaths(),
                static::ACCESS_MODE_PUBLIC,
            ],
            [
                $this->config->getBackOfficeRoutePattern(),
                SecurityOauthUserConfig::ROLE_BACK_OFFICE_USER,
            ],
            [
                $this->config->getBackOfficeRoutePattern(),
                SecurityOauthUserConfig::ROLE_OAUTH_USER,
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
        $container->set(static::SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR, function () {
            return $this->authenticator;
        });
    }
}
