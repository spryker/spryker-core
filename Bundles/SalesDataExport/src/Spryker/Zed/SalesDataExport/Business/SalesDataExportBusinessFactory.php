<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolver;
use Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolverInterface;
use Spryker\Zed\SalesDataExport\Business\Exporter\CsvExporter;
use Spryker\Zed\SalesDataExport\Business\Exporter\CsvExporterInterface;
use Spryker\Zed\SalesDataExport\Business\Reader\CsvReaderInterface;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderItemCsvReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderCsvReader;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface;
use Spryker\Zed\SalesDataExport\SalesDataExportDependencyProvider;

/**
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 * @method \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportRepositoryInterface getRepository()
 */
class SalesDataExportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Exporter\CsvExporterInterface
     */
    public function createOrderCsvExporter(): CsvExporterInterface
    {
        return new CsvExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createOrderCsvReader(),
            $this->createSalesDataExportConfigurationResolver()
        );
    }

    public function createOrderItemCsvExporter(): CsvExporterInterface
    {
        return new CsvExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createOrderCsvReader(),
            $this->createSalesDataExportConfigurationResolver()
        );
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Reader\CsvReaderInterface
     */
    public function createOrderCsvReader(): CsvReaderInterface
    {
        return new OrderCsvReader($this->getRepository());
    }

    public function createOrderItemCsvReader(): CsvReaderInterface
    {
        return new OrderItemCsvReader($this->getRepository());
    }

    public function createSalesDataExportConfigurationResolver(): SalesDataExportConfigurationResolverInterface
    {
        return new SalesDataExportConfigurationResolver(
            $this->getConfig(),
            $this->getDataExportService()
        );
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface
     */
    public function getDataExportService(): SalesDataExportToDataExportServiceInterface
    {
        return $this->getProvidedDependency(SalesDataExportDependencyProvider::SERVICE_DATA_EXPORT);
    }
}
