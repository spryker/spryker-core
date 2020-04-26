<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business;

use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\DataExport\Business\Exporter\DataExportHandler;
use Spryker\Zed\DataExport\Dependency\Plugin\DataExportConfigurationAdjusterPluginInterface;
use Spryker\Zed\DataExport\Dependency\Plugin\DataEntityExporterPluginInterface;
use Spryker\Zed\DataExport\Dependency\Plugin\DataExportConnectionPluginInterface;
use Spryker\Zed\DataExport\Dependency\Plugin\DataExportWriterPluginInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesDataExport\Communication\Plugin\DataExport\OrderExpenseExporterPlugin;
use Spryker\Zed\SalesDataExport\Communication\Plugin\DataExport\OrderExporterPlugin;
use Spryker\Zed\SalesDataExport\Communication\Plugin\DataExport\OrderItemExporterPlugin;
use Spryker\Zed\SalesDataExport\Communication\Plugin\DataExport\SalesDataExportConfigurationAdjusterPlugin;

/**
 * @method \Spryker\Zed\DataExport\DataExportConfig getConfig()
 * @method \Spryker\Zed\DataExport\Persistence\DataExportEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DataExport\Persistence\DataExportRepositoryInterface getRepository()
 */
class DataExportBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return DataExportHandler
     */
    public function createDataExportHandler() : DataExportHandler
    {
        return new DataExportHandler(
            $this->getDataEntityExporterPlugins(),
            $this->getDataExportConnectionPlugins(),
            $this->getDataExportWriterPlugins(),
            $this->getDataExportConfigurationAdjusterPlugins(),
            $this->getService(),
            $this->getConfig()
        );
    }

    protected function getService(): DataExportService
    {
        return new DataExportService();
    }

    /**
     * @return DataEntityExporterPluginInterface[]
     */
    protected function getDataEntityExporterPlugins(): array
    {
        return [
            new OrderExpenseExporterPlugin(),
            new OrderExporterPlugin(),
            new OrderItemExporterPlugin(),
        ];
    }

    /**
     * @return DataExportConnectionPluginInterface[]
     */
    protected function getDataExportConnectionPlugins(): array
    {
        return [
        ];
    }

    /**
     * @return DataExportWriterPluginInterface[]
     */
    protected function getDataExportWriterPlugins(): array
    {
        return [
        ];
    }

    /**
     * @return DataExportConfigurationAdjusterPluginInterface[]
     */
    protected function getDataExportConfigurationAdjusterPlugins(): array
    {
        return [
            new SalesDataExportConfigurationAdjusterPlugin(),
        ];
    }
}
