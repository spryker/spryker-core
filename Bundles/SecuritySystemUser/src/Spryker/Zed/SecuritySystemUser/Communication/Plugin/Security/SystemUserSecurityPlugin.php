<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Communication\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecuritySystemUser\Communication\Plugin\Security\Authenticator\TokenAuthenticator;
use Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig;

/**
 * @method \Spryker\Zed\SecuritySystemUser\Communication\SecuritySystemUserCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig getConfig()
 */
class SystemUserSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    protected const SECURITY_FIREWALL_NAME = 'SystemUser';
    protected const SECURITY_SYSTEM_USER_TOKEN_AUTHENTICATOR = 'security.system_user.token.authenticator';

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
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        $container->set(static::SECURITY_SYSTEM_USER_TOKEN_AUTHENTICATOR, function () {
            return new TokenAuthenticator();
        });

        $securityBuilder = $this->addFirewall($securityBuilder);
        $securityBuilder = $this->addAccessRules($securityBuilder);

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
            'pattern' => '^/(.+)/gateway/',
            'guard' => [
                'authenticators' => [
                    static::SECURITY_SYSTEM_USER_TOKEN_AUTHENTICATOR,
                ],
            ],
            'users' => function () {
                return $this->getFactory()->createSystemUserProvider();
            },
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
        $securityBuilder->addAccessRules([
            [
                '^/(.+)/gateway/',
                SecuritySystemUserConfig::ROLE_SYSTEM_USER,
            ],
        ]);

        return $securityBuilder;
    }
}
