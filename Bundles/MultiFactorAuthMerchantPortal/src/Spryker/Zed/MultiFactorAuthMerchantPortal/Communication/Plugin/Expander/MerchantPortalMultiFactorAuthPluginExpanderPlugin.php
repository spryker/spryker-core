<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Plugin\Expander;

use Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\MultiFactorAuthMerchantPortalCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalConfig getConfig()
 */
class MerchantPortalMultiFactorAuthPluginExpanderPlugin extends AbstractPlugin implements MultiFactorAuthPluginExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $plugins
     *
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    public function expand(array $plugins): array
    {
        return array_merge($plugins, $this->getFactory()->getUserMultiFactorAuthPlugins());
    }
}
