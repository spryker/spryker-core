<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddressDataImport\Communication\Plugin\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StockAddressDataImport\StockAddressDataImportConfig;

/**
 * @method \Spryker\Zed\StockAddressDataImport\Business\StockAddressDataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\StockAddressDataImport\StockAddressDataImportConfig getConfig()
 */
class StockAddressDataImportPlugin extends AbstractPlugin implements DataImportPluginInterface
{
    /**
     * {@inheritDoc}
     * - Executes stock address data importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function import(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer
    {
        return $this->getFacade()->importStockAddress($dataImporterConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns type of stock address data importer.
     *
     * @api
     *
     * @return string
     */
    public function getImportType(): string
    {
        return StockAddressDataImportConfig::IMPORT_TYPE_STOCK_ADDRESS;
    }
}
