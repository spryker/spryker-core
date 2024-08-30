<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\IncrementalInstaller\IncrementalInstallerConfig getConfig()
 */
class IncrementalInstallerDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_INCREMENTAL_INSTALLER = 'PLUGINS_INCREMENTAL_INSTALLER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addIncrementalInstallerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addIncrementalInstallerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_INCREMENTAL_INSTALLER, function () {
            return $this->getIncrementalInstallerPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\IncrementalInstallerExtension\Dependency\Plugin\IncrementalInstallerPluginInterface>
     */
    protected function getIncrementalInstallerPlugins(): array
    {
        return [];
    }
}
