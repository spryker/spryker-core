<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesDataExport\Business\Exporter\ExporterInterface;
use Spryker\Zed\SalesDataExport\Business\Exporter\LineExporter;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderExpenseReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderItemReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderReader;
use Spryker\Zed\SalesDataExport\Business\Reader\ReaderInterface;
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
    public function createOrderExporter(): ExporterInterface
    {
        return new LineExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createOrderReader()
        );
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Exporter\ExporterInterface
     */
    public function createOrderItemExporter(): ExporterInterface
    {
        return new LineExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createOrderItemReader()
        );
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Exporter\ExporterInterface
     */
    public function createOrderExpenseExporter(): ExporterInterface
    {
        return new LineExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createOrderExpenseReader()
        );
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Reader\ReaderInterface
     */
    public function createOrderReader(): ReaderInterface
    {
        return new OrderReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Reader\ReaderInterface
     */
    public function createOrderItemReader(): ReaderInterface
    {
        return new OrderItemReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Business\Reader\ReaderInterface
     */
    public function createOrderExpenseReader(): ReaderInterface
    {
        return new OrderExpenseReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface
     */
    public function getDataExportService(): SalesDataExportToDataExportServiceInterface
    {
        return $this->getProvidedDependency(SalesDataExportDependencyProvider::SERVICE_DATA_EXPORT);
    }
}
