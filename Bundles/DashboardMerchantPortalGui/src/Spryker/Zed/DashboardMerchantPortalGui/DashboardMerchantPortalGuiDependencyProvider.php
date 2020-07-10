<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DashboardMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DashboardMerchantPortalGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_DASHBOARD_CARD = 'PLUGINS_DASHBOARD_CARD';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addDashboardCardPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDashboardCardPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DASHBOARD_CARD, function () {
            return $this->getDashboardCardPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\DashboardMerchantPortalGuiExtension\Dependency\Plugin\MerchantDashboardCardPluginInterface[]
     */
    protected function getDashboardCardPlugins(): array
    {
        return [];
    }
}
