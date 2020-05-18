<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExportExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatResponseTransfer;

interface DataExportFormatterPluginInterface
{
    /**
     * Specification:
     * - Checks if plugin is applicable for formatting configuration defined in DataExportConfigurationTransfer.
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
     * - Formats data from `DataExportBatchTransfer` to a format specified in `DataExportConfigurationTransfer`.
     * - Returns `DataExportFormatResponseTransfer` with isSuccessful=true if data was formatted successfully.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportFormatResponseTransfer
     */
    public function format(
        DataExportBatchTransfer $dataExportBatchTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportFormatResponseTransfer;

    /**
     * Specification:
     * - Returns file extension according to format configuration in DataExportConfigurationTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return string
     */
    public function getExtension(DataExportConfigurationTransfer $dataExportConfigurationTransfer): string;
}
