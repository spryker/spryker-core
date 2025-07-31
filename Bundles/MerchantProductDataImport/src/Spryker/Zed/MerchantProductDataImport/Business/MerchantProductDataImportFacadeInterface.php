<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

interface MerchantProductDataImportFacadeInterface
{
    /**
     * Specification:
     * - Imports data for relations between abstract products and merchants.
     * - Iterates over the data sets and imports the data into the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importMerchantProduct(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer;

    /**
     * Specification:
     * - Imports abstract and product data including category relation, price and stock data.
     * - Iterates over the data sets and persists data into the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importMerchantCombinedProduct(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer;
}
