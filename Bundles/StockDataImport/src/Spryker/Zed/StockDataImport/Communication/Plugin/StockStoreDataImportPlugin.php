<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StockDataImport\Communication\Plugin;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StockDataImport\StockDataImportConfig;

/**
 * @method \Spryker\Zed\StockDataImport\Business\StockDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\StockDataImport\StockDataImportConfig getConfig()
 */
class StockStoreDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Executes stock store data importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importStockStore($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns type of stock store data importer.
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return StockDataImportConfig::IMPORT_TYPE_STOCK_STORE;
    }
}
