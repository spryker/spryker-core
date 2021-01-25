<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\StorageDatabase\Dependency\Service\StorageDatabaseToUtilEncodingBridge;
use Spryker\Client\StorageDatabase\Exception\MissingStorageReaderPluginException;
use Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface;

/**
 * @method \Spryker\Client\StorageDatabase\StorageDatabaseConfig getConfig()
 */
class StorageDatabaseDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const PLUGIN_STORAGE_READER_PROVIDER = 'PLUGIN_STORAGE_READER_PROVIDER';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addUtilEncodingService($container);
        $container = $this->addStorageReaderProviderPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new StorageDatabaseToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageReaderProviderPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_STORAGE_READER_PROVIDER, function (Container $container) {
            return $this->getStorageReaderProviderPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Client\StorageDatabase\Exception\MissingStorageReaderPluginException
     *
     * @return \Spryker\Client\StorageDatabaseExtension\Dependency\Plugin\StorageReaderPluginInterface
     */
    protected function getStorageReaderProviderPlugin(): StorageReaderPluginInterface
    {
        throw new MissingStorageReaderPluginException(
            sprintf(
                'There is no registered plugin which can perform storage database interaction.
                    Make sure that StorageDatabaseDependencyProvider::getStorageReaderProviderPlugin() returns
                    an implementation of %s',
                StorageReaderPluginInterface::class
            )
        );
    }
}
