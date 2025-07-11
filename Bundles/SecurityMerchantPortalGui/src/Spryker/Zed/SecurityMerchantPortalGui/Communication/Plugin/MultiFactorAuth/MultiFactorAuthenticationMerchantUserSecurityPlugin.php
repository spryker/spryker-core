<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class MultiFactorAuthenticationMerchantUserSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    /**
     * @var string
     */
    protected const FIREWALL_MERCHANT_USER_MULTI_FACTOR_AUTH = 'MerchantUserMultiFactorAuthentication';

    /**
     * {@inheritDoc}
     * - Adds a dedicated firewall for merchant user Multi-Factor authentication paths.
     * - Uses the merchant portal route pattern from configuration to match all relevant URLs.
     * - Registers merchant user provider to ensure proper user resolution during Multi-Factor authentication flows.
     * - Sets security to false as authentication is handled by the main merchant portal firewall.
     * - Ensures token refresh operations can properly identify merchant users after Multi-Factor authentication actions.
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
        $securityBuilder->addFirewall(static::FIREWALL_MERCHANT_USER_MULTI_FACTOR_AUTH, [
            'pattern' => $this->getConfig()->getMerchantPortalRoutePattern(),
            'users' => function () {
                return $this->getFactory()->createMerchantUserProvider();
            },
            'security' => false,
        ]);

        return $securityBuilder;
    }
}
