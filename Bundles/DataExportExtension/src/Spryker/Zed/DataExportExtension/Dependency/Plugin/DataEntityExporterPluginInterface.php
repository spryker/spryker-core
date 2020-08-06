<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExportExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;

interface DataEntityExporterPluginInterface
{
    /**
     * Specification:
     * - Returns supported data entity name.
     *
     * @api
     *
     * @return string
     */
    public static function getDataEntity(): string;

    /**
     * Specification:
     * - Exports data according to configuration in `DataExportConfigurationTransfer`.
     * - Exports data independently from format and connection settings.
     * - Returns results of export in `DataExportReportTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function export(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer;
}
