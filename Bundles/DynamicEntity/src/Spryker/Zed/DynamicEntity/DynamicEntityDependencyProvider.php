<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity;

use Propel\Runtime\Propel;
use Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionAdapter;
use Spryker\Zed\DynamicEntity\Dependency\Service\DynamicEntityToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\DynamicEntity\DynamicEntityConfig getConfig()
 */
class DynamicEntityDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CONNECTION = 'CONNECTION';

    /**
     * @var string
     */
    public const PLUGINS_DYNAMIC_ENTITY_POST_CREATE = 'PLUGINS_DYNAMIC_ENTITY_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_DYNAMIC_ENTITY_POST_UPDATE = 'PLUGINS_DYNAMIC_ENTITY_POST_UPDATE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addDynamicEntityPostCreatePlugins($container);
        $container = $this->addDynamicEntityPostUpdatePlugins($container);
        $container = $this->addConnection($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConnection(Container $container): Container
    {
        $container->set(static::CONNECTION, function () {
            return new DynamicEntityToConnectionAdapter(Propel::getConnection());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDynamicEntityPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DYNAMIC_ENTITY_POST_CREATE, function () {
            return $this->getDynamicEntityPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface>
     */
    protected function getDynamicEntityPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDynamicEntityPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DYNAMIC_ENTITY_POST_UPDATE, function () {
            return $this->getDynamicEntityPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface>
     */
    protected function getDynamicEntityPostUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new DynamicEntityToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }
}
