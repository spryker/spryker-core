<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

interface ContentProductDataImportFacadeInterface
{
    /**
     * Specification:
     * - Imports content item abstract product list from the specified file.
     * - Iterates over the data sets and imports the data into persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importProductAbstractListTerm(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer): DataImporterReportTransfer;
}
