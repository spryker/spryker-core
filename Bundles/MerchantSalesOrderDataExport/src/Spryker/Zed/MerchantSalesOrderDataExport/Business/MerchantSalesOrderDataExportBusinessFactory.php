<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Exporter\MerchantSalesOrderDataExporter;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Exporter\MerchantSalesOrderDataExporterInterface;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\DataReaderInterface;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\MerchantOrderDataReader;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\MerchantOrderExpenseDataReader;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\MerchantOrderItemDataReader;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\MerchantReader;
use Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToDataExportServiceInterface;
use Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\MerchantSalesOrderDataExportConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesOrderDataExport\Persistence\MerchantSalesOrderDataExportRepositoryInterface getRepository()
 */
class MerchantSalesOrderDataExportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Business\Exporter\MerchantSalesOrderDataExporterInterface
     */
    public function createMerchantOrderExporter(): MerchantSalesOrderDataExporterInterface
    {
        return new MerchantSalesOrderDataExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createMerchantReader(),
            $this->createMerchantOrderDataReader()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Business\Exporter\MerchantSalesOrderDataExporterInterface
     */
    public function createMerchantOrderItemExporter(): MerchantSalesOrderDataExporterInterface
    {
        return new MerchantSalesOrderDataExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createMerchantReader(),
            $this->createMerchantOrderItemDataReader()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Business\Exporter\MerchantSalesOrderDataExporterInterface
     */
    public function createMerchantOrderExpenseExporter(): MerchantSalesOrderDataExporterInterface
    {
        return new MerchantSalesOrderDataExporter(
            $this->getDataExportService(),
            $this->getConfig(),
            $this->createMerchantReader(),
            $this->createMerchantOrderExpenseDataReader()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\DataReaderInterface
     */
    public function createMerchantOrderDataReader(): DataReaderInterface
    {
        return new MerchantOrderDataReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\DataReaderInterface
     */
    public function createMerchantOrderItemDataReader(): DataReaderInterface
    {
        return new MerchantOrderItemDataReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\DataReaderInterface
     */
    public function createMerchantOrderExpenseDataReader(): DataReaderInterface
    {
        return new MerchantOrderExpenseDataReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Business\Reader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToDataExportServiceInterface
     */
    public function getDataExportService(): MerchantSalesOrderDataExportToDataExportServiceInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderDataExportDependencyProvider::SERVICE_DATA_EXPORT);
    }
}
