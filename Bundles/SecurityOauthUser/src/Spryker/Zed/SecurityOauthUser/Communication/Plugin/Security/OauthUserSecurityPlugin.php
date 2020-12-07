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
     * - Extends security service with SystemUser firewall.
     *
     * @api
     *
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(
        SecurityBuilderInterface $securityBuilder,
        ContainerInterface $container
    ): SecurityBuilderInterface {
        $container->set(static::SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR, function () {
            return new OauthUserTokenAuthenticator();
        });

        $securityBuilder = $this->addFirewall($securityBuilder);
        $securityBuilder = $this->addAccessRules($securityBuilder);
        $securityBuilder = $this->extendSecurityUserFirewall($securityBuilder);

        return $securityBuilder;
    }

    /**
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function addFirewall(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
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
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    protected function extendSecurityUserFirewall(SecurityBuilderInterface $securityBuilder)
    {
        $firewalls = (clone $securityBuilder)->getConfiguration()->getFirewalls();

        /** @var string $firewallName */
        foreach ($firewalls as $firewallName => $firewallConfiguration) {
            if ($firewallName !== static::SECURITY_USER_FIREWALL_NAME) {
                continue;
            }

            $securityBuilder->addFirewall(static::SECURITY_USER_FIREWALL_NAME, [
                    'guard' => [
                        'authenticators' => [
                            static::SECURITY_OAUTH_USER_TOKEN_AUTHENTICATOR,
                        ],
                    ],
                    'users' => function () use ($firewallConfiguration) {
                        return new ChainUserProvider([
                            $firewallConfiguration['users'](),
                            $this->getFactory()->createOauthUserProvider(),
                        ]);
                    },
                ] + $firewallConfiguration);
        }

        return $securityBuilder;
    }
}
