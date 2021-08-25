<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityOauthUser\Communication\Plugin\Security\Authenticator\OauthUserTokenAuthenticator;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;
use Symfony\Component\Security\Core\User\ChainUserProvider;

/**
 * This plugin must be connected after or instead of {@link \Spryker\Zed\SecurityGui\Communication\Plugin\Security\UserSecurityPlugin}.
 * These two plugins have an intersection in the pattern ("^/"), only the first firewall will be executed with the same pattern.
 * For this reason new plugin should expand the already existing firewall and DO NOT create an additional firewall with the same pattern.
 *
 * @method \Spryker\Zed\SecurityOauthUser\Communication\SecurityOauthUserCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig getConfig()
 * @method \Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserFacadeInterface getFacade()
 */
class OauthUserSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    protected const SECURITY_FIREWALL_NAME = 'OauthUser';
    protected const SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR = 'security.oauth_user.token.authenticator';

    /**
     * @uses \Spryker\Zed\SecurityGui\Communication\Plugin\Security\UserSecurityPlugin::SECURITY_FIREWALL_NAME
     */
    protected const SECURITY_USER_FIREWALL_NAME = 'User';

    /**
     * @uses \Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY
     */
    protected const IS_AUTHENTICATED_ANONYMOUSLY = 'IS_AUTHENTICATED_ANONYMOUSLY';

    /**
     * {@inheritDoc}
     * - Extends `User` firewall with authenticator and user provider if exists.
     * - Introduces `OauthUser` firewall in security service otherwise.
     * - Allows form authentication and Oauth User authentication jointly.
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
        $container->set(static::SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR, function () {
            return new OauthUserTokenAuthenticator();
        });

        $securityBuilder = $this->extendUserFirewall($securityBuilder);

        if ($this->findFirewall(static::SECURITY_USER_FIREWALL_NAME, $securityBuilder) === null) {
            $securityBuilder = $this->addOauthUserFirewall($securityBuilder);
        }

        $securityBuilder = $this->addAccessRules($securityBuilder);

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
                'guard' => [
                    'authenticators' => [
                        static::SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR,
                    ],
                ],
                'users' => function () use ($userFirewallConfiguration) {
                    return new ChainUserProvider([
                        $userFirewallConfiguration['users'](),
                        $this->getFactory()->createOauthUserProvider(),
                    ]);
                },
            ] + $userFirewallConfiguration);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addOauthUserFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        $securityBuilder->addFirewall(static::SECURITY_FIREWALL_NAME, [
            'anonymous' => true,
            'pattern' => $this->getConfig()->getBackOfficeRoutePattern(),
            'guard' => [
                'authenticators' => [
                    static::SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR,
                ],
            ],
            'logout' => [
                'login_path' => $this->getConfig()->getUrlLogin(),
                'logout_path' => $this->getConfig()->getUrlLogout(),
            ],
            'users' => function () {
                return $this->getFactory()->createOauthUserProvider();
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
        $accessRules = [
            [
                $this->getConfig()->getIgnorablePaths(),
                static::IS_AUTHENTICATED_ANONYMOUSLY,
            ],
            [
                $this->getConfig()->getBackOfficeRoutePattern(),
                SecurityOauthUserConfig::ROLE_BACK_OFFICE_USER,
            ],
            [
                $this->getConfig()->getBackOfficeRoutePattern(),
                SecurityOauthUserConfig::ROLE_OAUTH_USER,
            ],
        ];

        $securityBuilder->addAccessRules($accessRules);

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
}
