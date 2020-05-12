<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport;

use Spryker\Service\DataExport\Dependency\External\DataExportToCsvWriterInterface;
use Spryker\Service\DataExport\Dependency\Service\DataExportToUtilDataReaderServiceInterface;
use Spryker\Service\DataExport\Formatter\DataExportCsvFormatter;
use Spryker\Service\DataExport\Formatter\DataExportFormatter;
use Spryker\Service\DataExport\Formatter\DataExportFormatterInterface;
use Spryker\Service\DataExport\Mapper\DataExportConfigurationMapper;
use Spryker\Service\DataExport\Mapper\DataExportConfigurationMapperInterface;
use Spryker\Service\DataExport\Merger\DataExportConfigurationMerger;
use Spryker\Service\DataExport\Merger\DataExportConfigurationMergerInterface;
use Spryker\Service\DataExport\Parser\DataExportConfigurationParserInterface;
use Spryker\Service\DataExport\Parser\DataExportConfigurationYamlParser;
use Spryker\Service\DataExport\Resolver\DataExportPathResolver;
use Spryker\Service\DataExport\Resolver\DataExportPathResolverInterface;
use Spryker\Service\DataExport\Writer\DataExportLocalWriter;
use Spryker\Service\DataExport\Writer\DataExportWriter;
use Spryker\Service\DataExport\Writer\DataExportWriterInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class DataExportServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\DataExport\Parser\DataExportConfigurationParserInterface
     */
    public function createDataExportConfigurationYamlParser(): DataExportConfigurationParserInterface
    {
        return new DataExportConfigurationYamlParser(
            $this->getUtilDataReaderService(),
            $this->createDataExportConfigurationMapper()
        );
    }

    /**
     * @return \Spryker\Service\DataExport\Mapper\DataExportConfigurationMapperInterface
     */
    public function createDataExportConfigurationMapper(): DataExportConfigurationMapperInterface
    {
        return new DataExportConfigurationMapper();
    }

    /**
     * @return \Spryker\Service\DataExport\Merger\DataExportConfigurationMergerInterface
     */
    public function createDataExportConfigurationMerger(): DataExportConfigurationMergerInterface
    {
        return new DataExportConfigurationMerger();
    }

    /**
     * @return \Spryker\Service\DataExport\Writer\DataExportWriterInterface
     */
    public function createDataExportWriter(): DataExportWriterInterface
    {
        return new DataExportWriter(
            $this->getDataExportConnectionPlugins(),
            $this->createDataExportFormatter(),
            $this->createDataExportLocalWriter(),
            $this->createDataExportPathResolver()
        );
    }

    /**
     * @return \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface
     */
    public function createDataExportFormatter(): DataExportFormatterInterface
    {
        return new DataExportFormatter(
            $this->getDataExportFormatterPlugins(),
            $this->createDataExportCsvFormatter()
        );
    }

    /**
     * @return \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface
     */
    public function createDataExportCsvFormatter(): DataExportFormatterInterface
    {
        return new DataExportCsvFormatter($this->getCsvWriter());
    }

    /**
     * @return \Spryker\Service\DataExport\Resolver\DataExportPathResolverInterface
     */
    public function createDataExportPathResolver(): DataExportPathResolverInterface
    {
        return new DataExportPathResolver();
    }

    /**
     * @return \Spryker\Service\DataExport\Writer\DataExportWriterInterface
     */
    public function createDataExportLocalWriter(): DataExportWriterInterface
    {
        return new DataExportLocalWriter($this->createDataExportFormatter());
    }

    /**
     * @return \Spryker\Service\DataExport\Dependency\Service\DataExportToUtilDataReaderServiceInterface
     */
    public function getUtilDataReaderService(): DataExportToUtilDataReaderServiceInterface
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::SERVICE_UTIL_DATA_READER);
    }

    /**
     * @return \Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportFormatterPluginInterface[]
     */
    public function getDataExportFormatterPlugins(): array
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::DATA_EXPORT_FORMATTER_PLUGINS);
    }

    /**
     * @return \Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportConnectionPluginInterface[]
     */
    public function getDataExportConnectionPlugins(): array
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::DATA_EXPORT_CONNECTION_PLUGINS);
    }

    /**
     * @return \Spryker\Service\DataExport\Dependency\External\DataExportToCsvWriterInterface
     */
    public function getCsvWriter(): DataExportToCsvWriterInterface
    {
        return $this->getProvidedDependency(DataExportDependencyProvider::CSV_WRITER);
    }
}
