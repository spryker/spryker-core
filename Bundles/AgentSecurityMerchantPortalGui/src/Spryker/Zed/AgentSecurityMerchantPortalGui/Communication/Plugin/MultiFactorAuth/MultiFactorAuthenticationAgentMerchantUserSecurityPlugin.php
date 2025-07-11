<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\AgentSecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig getConfig()
 */
class MultiFactorAuthenticationAgentMerchantUserSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    /**
     * @var string
     */
    protected const FIREWALL_AGENT_MERCHANT_USER_MULTI_FACTOR_AUTH = 'AgentMerchantUserMultiFactorAuthentication';

    /**
     * {@inheritDoc}
     * - Adds a dedicated firewall for agent merchant user multi-factor authentication paths.
     * - Uses the merchant portal route pattern from configuration to match all relevant URLs.
     * - Registers agent merchant user provider to ensure proper user resolution during Multi-Factor Authentication flows.
     * - Sets security to false as authentication is handled by the agent merchant portal firewall.
     * - Ensures token refresh operations can properly identify agent merchant users after Multi-Factor Authentication actions.
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
        $securityBuilder->addFirewall(static::FIREWALL_AGENT_MERCHANT_USER_MULTI_FACTOR_AUTH, [
            'pattern' => $this->getConfig()->getRoutePatternAgentMerchantPortal(),
            'users' => function () {
                return $this->getFactory()->createAgentMerchantUserProvider();
            },
            'security' => false,
        ]);

        return $securityBuilder;
    }
}
