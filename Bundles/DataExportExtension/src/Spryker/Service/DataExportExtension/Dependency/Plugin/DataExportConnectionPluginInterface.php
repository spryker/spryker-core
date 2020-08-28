<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExportExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatResponseTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;

interface DataExportConnectionPluginInterface
{
    /**
     * Specification:
     * - Checks if plugin is applicable for connection configuration defined in DataExportConfigurationTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return bool
     */
    public function isApplicable(DataExportConfigurationTransfer $dataExportConfigurationTransfer): bool;

    /**
     * Specification:
     * - Writes formatted data from `DataExportFormatResponseTransfer` according to connection configuration in `DataExportConfigurationTransfer`.
     * - Returns `DataExportWriteResponseTransfer` with isSuccessful=true if data was written successfully.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportFormatResponseTransfer $dataExportFormatResponseTransfer
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    public function write(
        DataExportFormatResponseTransfer $dataExportFormatResponseTransfer,
        DataExportBatchTransfer $dataExportBatchTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportWriteResponseTransfer;
}
