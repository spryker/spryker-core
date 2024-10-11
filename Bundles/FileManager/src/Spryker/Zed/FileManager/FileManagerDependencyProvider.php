<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager;

use Spryker\Zed\FileManager\Dependency\Service\FileManagerToFileSystemServiceBridge;
use Spryker\Zed\FileManager\Dependency\Service\FileManagerToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\FileManager\FileManagerConfig getConfig()
 */
class FileManagerDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_FILE_SYSTEM = 'SERVICE_FILE_SYSTEM';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGINS_FILE_MANAGER_DATA_COLLECTION_EXPANDER = 'PLUGINS_FILE_MANAGER_DATA_COLLECTION_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_FILE_MANAGER_DATA_COLLECTION_EXPANDER_PRE_SAVE = 'PLUGINS_FILE_MANAGER_DATA_COLLECTION_EXPANDER_PRE_SAVE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addFileSystemService($container);
        $container = $this->addFileManagerDataCollectionExpanderPlugins($container);
        $container = $this->addFileManagerDataCollectionExpanderPreSavePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
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
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new FileManagerToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileSystemService(Container $container)
    {
        $container->set(static::SERVICE_FILE_SYSTEM, function (Container $container) {
            return new FileManagerToFileSystemServiceBridge(
                $container->getLocator()->fileSystem()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileManagerDataCollectionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FILE_MANAGER_DATA_COLLECTION_EXPANDER, function () {
            return $this->getFileManagerDataCollectionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileManagerDataCollectionExpanderPreSavePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FILE_MANAGER_DATA_COLLECTION_EXPANDER_PRE_SAVE, function () {
            return $this->getFileManagerDataCollectionExpanderPreSavePlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\FileManagerExtension\Dependency\Plugin\FileManagerDataCollectionExpanderPluginInterface>
     */
    protected function getFileManagerDataCollectionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\FileManagerExtension\Dependency\Plugin\FileManagerDataCollectionExpanderPreSavePluginInterface>
     */
    protected function getFileManagerDataCollectionExpanderPreSavePlugins(): array
    {
        return [];
    }
}
