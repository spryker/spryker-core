<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport;

use Propel\Runtime\Propel;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventBridge;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchBridge;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DataImportDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_TOUCH = 'touch facade';
    const FACADE_EVENT = 'event facade';
    const PROPEL_CONNECTION = 'propel connection';
    const DATA_IMPORTER_PLUGINS = 'IMPORTER_PLUGINS';
    const DATA_IMPORT_BEFORE_HOOK_PLUGINS = 'DATA_IMPORT_BEFORE_HOOK_PLUGINS';
    const DATA_IMPORT_AFTER_HOOK_PLUGINS = 'DATA_IMPORT_AFTER_HOOK_PLUGINS';
    const DATA_IMPORT_DEFAULT_WRITER_PLUGINS = 'DATA_IMPORT_DEFAULT_WRITER_PLUGINS';
    const STORE = 'store';

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
     * @return \Spryker\Zed\DataImport\Dependency\Plugin\DataImportWriterPluginInterface|\Spryker\Zed\DataImport\Dependency\Plugin\DataImportFlushPluginInterface[]
     */
    protected function getDataImportDefaultWriterPlugins(): array
    {
        return [];
    }
}
