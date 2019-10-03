<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

interface StockDataImportFacadeInterface
{
    /**
     * Specification:
     * - Imports stock data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importStock(?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null): DataImporterReportTransfer;
}
