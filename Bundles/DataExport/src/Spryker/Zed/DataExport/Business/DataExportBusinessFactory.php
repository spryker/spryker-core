<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business;

use Spryker\Service\DataExport\DataExportServiceInterface;
use Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProvider;
use Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProviderInterface;
use Spryker\Zed\DataExport\Business\Exporter\DataExportExecutor;
use Spryker\Zed\DataExport\Business\Exporter\DataExportGeneratorExporter;
use Spryker\Zed\DataExport\Business\Exporter\DataExportGeneratorExporterInterface;
use Spryker\Zed\DataExport\Business\Mapper\DataExportMapper;
use Spryker\Zed\DataExport\Business\Mapper\DataExportMapperInterface;
use Spryker\Zed\DataExport\DataExportDependencyProvider;
use Spryker\Zed\DataExport\Dependency\Facade\DataExportToGracefulRunnerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DataExport\DataExportConfig getConfig()
 */
class DataExportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataExport\Business\Exporter\DataExportExecutor
     */
    public function createDataExportHandler(): DataExportExecutor
    {
        return new DataExportExecutor(
            $this->createDataExportPluginProvider(),
            $this->getDataExportService(),
            $this->getConfig(),
            $this->getGracefulRunnerFacade(),
            $this->createDataExportGeneratorExporter(),
        );
    }

    /**
     * @return \Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProviderInterface
     */
    public function createDataExportPluginProvider(): DataExportPluginProviderInterface
    {
        return new DataExportPluginProvider(
            $this->getDataEntityExporterPlugins(),
            $this->getDataEntityGeneratorPlugins(),
            $this->getDataEntityReaderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\DataExport\Business\Exporter\DataExportGeneratorExporterInterface
     */
    public function createDataExportGeneratorExporter(): DataExportGeneratorExporterInterface
    {
        return new DataExportGeneratorExporter($this->getDataExportService(), $this->createDataExportMapper());
    }

    /**
     * @return \Spryker\Zed\DataExport\Business\Mapper\DataExportMapperInterface
     */
    public function createDataExportMapper(): DataExportMapperInterface
    {
        return new DataExportMapper();
    }

    /**
     * @return \Spryker\Service\DataExport\DataExportServiceInterface
     */
    public function getDataExportService(): DataExportServiceInterface
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::SERVICE_DATA_EXPORT);
    }

    /**
     * @return array<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface>
     */
    public function getDataEntityExporterPlugins(): array
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::DATA_ENTITY_EXPORTER_PLUGINS);
    }

    /**
     * @return \Spryker\Zed\DataExport\Dependency\Facade\DataExportToGracefulRunnerFacadeInterface
     */
    public function getGracefulRunnerFacade(): DataExportToGracefulRunnerFacadeInterface
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::FACADE_GRACEFUL_RUNNER);
    }

    /**
     * @return array<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityReaderPluginInterface>
     */
    public function getDataEntityReaderPlugins(): array
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::DATA_ENTITY_READER_PLUGINS);
    }

    /**
     * @return array<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityGeneratorPluginInterface>
     */
    public function getDataEntityGeneratorPlugins(): array
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::DATA_ENTITY_GENERATOR_PLUGINS);
    }
}
