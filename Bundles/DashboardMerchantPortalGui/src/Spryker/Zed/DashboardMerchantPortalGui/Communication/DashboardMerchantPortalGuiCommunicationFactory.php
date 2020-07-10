<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DashboardMerchantPortalGui\Communication;

use Spryker\Zed\DashboardMerchantPortalGui\DashboardMerchantPortalGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class DashboardMerchantPortalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\DashboardMerchantPortalGuiExtension\Dependency\Plugin\MerchantDashboardCardPluginInterface[]
     */
    public function getDashboardCardPlugins(): array
    {
        return $this->getProvidedDependency(DashboardMerchantPortalGuiDependencyProvider::PLUGINS_DASHBOARD_CARD);
    }
}
