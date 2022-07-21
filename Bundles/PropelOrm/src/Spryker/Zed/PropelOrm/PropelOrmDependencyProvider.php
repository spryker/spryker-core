<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class PropelOrmDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_FIND_EXTENSION = 'PLUGINS_FIND_EXTENSION';

    /**
     * @var string
     */
    public const PLUGINS_POST_SAVE_EXTENSION = 'PLUGINS_POST_SAVE_EXTENSION';

    /**
     * @var string
     */
    public const PLUGINS_POST_UPDATE_EXTENSION = 'PLUGINS_POST_UPDATE_EXTENSION';

    /**
     * @var string
     */
    public const PLUGINS_POST_DELETE_EXTENSION = 'PLUGINS_POST_DELETE_EXTENSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addFindExtensionPlugins($container);
        $container = $this->addPostSaveExtensionPlugins($container);
        $container = $this->addPostUpdateExtensionPlugins($container);
        $container = $this->addPostDeleteExtensionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFindExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FIND_EXTENSION, function (Container $container) {
            return $this->getFindExtensionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostSaveExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_SAVE_EXTENSION, function (Container $container) {
            return $this->getPostSaveExtensionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostUpdateExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_UPDATE_EXTENSION, function (Container $container) {
            return $this->getPostUpdateExtensionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostDeleteExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_DELETE_EXTENSION, function (Container $container) {
            return $this->getPostDeleteExtensionPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\PropelOrmExtension\Dependency\Plugin\FindExtensionPluginInterface>
     */
    protected function getFindExtensionPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\PropelOrmExtension\Dependency\Plugin\PostSaveExtensionPluginInterface>
     */
    protected function getPostSaveExtensionPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\PropelOrmExtension\Dependency\Plugin\PostUpdateExtensionPluginInterface>
     */
    protected function getPostUpdateExtensionPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\PropelOrmExtension\Dependency\Plugin\PostDeleteExtensionPluginInterface>
     */
    protected function getPostDeleteExtensionPlugins(): array
    {
        return [];
    }
}
