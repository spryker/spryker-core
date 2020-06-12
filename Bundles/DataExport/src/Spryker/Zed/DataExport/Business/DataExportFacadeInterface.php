<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;

interface DataExportFacadeInterface
{
    /**
     * Specification:
     * - Merges `DataExportConfigurationsTransfer` with the module's default configuration.
     * - Uses stack of `DataEntityExporterPluginInterface` plugins to export data.
     * - Returns a collection of `DataExportReportTransfer` collected from `DataEntityExporterPluginInterface` plugins execution results.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer[]
     */
    public function exportDataEntities(DataExportConfigurationsTransfer $dataExportConfigurationsTransfer): array;
}
