<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport;

use Spryker\Service\DataExport\Dependency\External\DataExportToCsvFormatterInterface;
use Spryker\Service\DataExport\Dependency\External\DataExportToLeagueCsvWriterAdapter;
use Spryker\Service\DataExport\Dependency\Service\DataExportToUtilDataReaderServiceBridge;
use Spryker\Service\DataExport\Dependency\Service\DataExportToUtilDataReaderServiceInterface;
use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

/**
 * @method \Spryker\Service\DataExport\DataExportConfig getConfig()
 */
class DataExportDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_DATA_READER = 'SERVICE_UTIL_DATA_READER';

    public const DATA_EXPORT_CONNECTION_PLUGINS = 'DATA_EXPORT_CONNECTION_PLUGINS';
    public const DATA_EXPORT_FORMATTER_PLUGINS = 'DATA_EXPORT_FORMATTER_PLUGINS';

    public const CSV_FORMATTER = 'CSV_FORMATTER';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = parent::provideServiceDependencies($container);
        $container = $this->addUtilDataReaderService($container);
        $container = $this->addDataExportFormatterPlugins($container);
        $container = $this->addDataExportConnectionPlugins($container);
        $container = $this->addCsvFormatter($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addUtilDataReaderService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATA_READER, function (Container $container): DataExportToUtilDataReaderServiceInterface {
            return new DataExportToUtilDataReaderServiceBridge(
                $container->getLocator()->utilDataReader()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addDataExportFormatterPlugins(Container $container): Container
    {
        $container->set(static::DATA_EXPORT_FORMATTER_PLUGINS, function (): array {
            return $this->getDataExportFormatterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addDataExportConnectionPlugins(Container $container): Container
    {
        $container->set(static::DATA_EXPORT_CONNECTION_PLUGINS, function (): array {
            return $this->getDataExportConnectionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addCsvFormatter(Container $container): Container
    {
        $container->set(static::CSV_FORMATTER, $container->factory(function (): DataExportToCsvFormatterInterface {
            return new DataExportToLeagueCsvWriterAdapter();
        }));

        return $container;
    }

    /**
     * @return \Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportFormatterPluginInterface[]
     */
    protected function getDataExportFormatterPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportConnectionPluginInterface[]
     */
    protected function getDataExportConnectionPlugins(): array
    {
        return [];
    }
}
