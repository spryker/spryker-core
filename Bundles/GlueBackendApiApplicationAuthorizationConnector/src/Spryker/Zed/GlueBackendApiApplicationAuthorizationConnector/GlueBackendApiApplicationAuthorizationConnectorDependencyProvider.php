<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\GlueBackendApiApplicationAuthorizationConnectorConfig getConfig()
 */
class GlueBackendApiApplicationAuthorizationConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_PROTECTED_PATH_COLLECTION_EXPANDER = 'PLUGINS_PROTECTED_PATH_COLLECTION_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addProtectedPathCollectionExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProtectedPathCollectionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PROTECTED_PATH_COLLECTION_EXPANDER, function () {
            return $this->getProtectedPathCollectionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\ProtectedPathCollectionExpanderPluginInterface>
     */
    protected function getProtectedPathCollectionExpanderPlugins(): array
    {
        return [];
    }
}
