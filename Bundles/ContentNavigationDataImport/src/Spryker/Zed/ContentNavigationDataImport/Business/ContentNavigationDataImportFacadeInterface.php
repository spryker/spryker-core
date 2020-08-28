<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentNavigationDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

interface ContentNavigationDataImportFacadeInterface
{
    /**
     * Specification:
     * - Imports content navigation data from the specified file.
     * - Iterates over the data sets and imports the data into persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importContentNavigation(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer;
}
