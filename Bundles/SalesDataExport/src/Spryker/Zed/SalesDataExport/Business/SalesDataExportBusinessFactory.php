<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolver;
use Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolverInterface;
use Spryker\Zed\SalesDataExport\Business\Exporter\ExporterInterface;
use Spryker\Zed\SalesDataExport\Business\Exporter\LineExporter;
use Spryker\Zed\SalesDataExport\Business\Reader\LineReaderInterface;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderExpenseLineReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderItemLineReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderLineReader;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface;
use Spryker\Zed\SalesDataExport\SalesDataExportDependencyProvider;

/**
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 * @method \Spryker\Zed\SalesDataExport\Persistence\SalesDataExportRepositoryInterface getRepository()
 */
class SalesDataExportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Exporter\ExporterInterface
     */
    public function createOrderLineExporter(): ExporterInterface
    {
        return new LineExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createOrderLineReader(),
            $this->createSalesDataExportConfigurationResolver()
        );
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Exporter\ExporterInterface
     */
    public function createOrderItemLineExporter(): ExporterInterface
    {
        return new LineExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createOrderItemLineReader(),
            $this->createSalesDataExportConfigurationResolver()
        );
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Exporter\ExporterInterface
     */
    public function createOrderExpenseLineExporter(): ExporterInterface
    {
        return new LineExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createOrderExpenseLineReader(),
            $this->createSalesDataExportConfigurationResolver()
        );
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Reader\LineReaderInterface
     */
    public function createOrderLineReader(): LineReaderInterface
    {
        return new OrderLineReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Reader\LineReaderInterface
     */
    public function createOrderItemLineReader(): LineReaderInterface
    {
        return new OrderItemLineReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Reader\LineReaderInterface
     */
    public function createOrderExpenseLineReader(): LineReaderInterface
    {
        return new OrderExpenseLineReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolverInterface
     */
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
