<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport;

use Propel\Runtime\Propel;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientBridge;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventBridge;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchBridge;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionBridge;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\DataImport\DataImportConfig getConfig()
 */
class DataImportDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_TOUCH = 'touch facade';
    public const FACADE_EVENT = 'event facade';
    public const PROPEL_CONNECTION = 'propel connection';
    public const DATA_IMPORTER_PLUGINS = 'IMPORTER_PLUGINS';
    public const DATA_IMPORT_BEFORE_HOOK_PLUGINS = 'DATA_IMPORT_BEFORE_HOOK_PLUGINS';
    public const DATA_IMPORT_AFTER_HOOK_PLUGINS = 'DATA_IMPORT_AFTER_HOOK_PLUGINS';
    public const DATA_IMPORT_DEFAULT_WRITER_PLUGINS = 'DATA_IMPORT_DEFAULT_WRITER_PLUGINS';
    public const CLIENT_QUEUE = 'CLIENT_QUEUE';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const STORE = 'store';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addTouchFacade($container);
        $container = $this->addEventFacade($container);
        $container = $this->addPropelConnection($container);
        $container = $this->addDataImporterPlugins($container);
        $container = $this->addStore($container);
        $container = $this->addDataImportBeforeImportHookPlugins($container);
        $container = $this->addDataImportAfterImportHookPlugins($container);
        $container = $this->addDataImportDefaultWriterPlugins($container);
        $container = $this->addQueueClient($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new DataImportToTouchBridge(
                $container->getLocator()->touch()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container)
    {
        $container[static::FACADE_EVENT] = function (Container $container) {
            return new DataImportToEventBridge(
                $container->getLocator()->event()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelConnection(Container $container)
    {
        $container[static::PROPEL_CONNECTION] = function () {
            return new DataImportToPropelConnectionBridge(
                Propel::getConnection()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataImporterPlugins(Container $container): Container
    {
        $container[static::DATA_IMPORTER_PLUGINS] = function () {
            return $this->getDataImporterPlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getDataImporterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataImportBeforeImportHookPlugins(Container $container): Container
    {
        $container[static::DATA_IMPORT_BEFORE_HOOK_PLUGINS] = function () {
            return $this->getDataImportBeforeImportHookPlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getDataImportBeforeImportHookPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataImportAfterImportHookPlugins(Container $container): Container
    {
        $container[static::DATA_IMPORT_AFTER_HOOK_PLUGINS] = function () {
            return $this->getDataImportAfterImportHookPlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getDataImportAfterImportHookPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataImportDefaultWriterPlugins(Container $container): Container
    {
        $container[static::DATA_IMPORT_DEFAULT_WRITER_PLUGINS] = function () {
            return $this->getDataImportDefaultWriterPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function getDataImportDefaultWriterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueueClient(Container $container): Container
    {
        $container[static::CLIENT_QUEUE] = function (Container $container) {
            return new DataImportToQueueClientBridge($container->getLocator()->queue()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new DataImportToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }
}
