<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;

interface DataExportServiceInterface
{
    /**
     * Specification:
     * - Reads configurations from the file defined in $filePath.
     * - Maps read configurations to DataExportConfigurationsTransfer.
     *
     * @api
     *
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function parseConfiguration(string $filePath): DataExportConfigurationsTransfer;

    /**
     * Specification:
     * - Merges two `DataExportConfigurationTransfer` transfers into one.
     * - Properties of the `$masterDataExportConfigurationTransfer` will override same properties in the `$slaveDataExportConfigurationTransfer`.
     * - Properties `DataExportConfigurationTransfer.hooks` and `DataExportConfigurationTransfer.filterCriteria` will be merged.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $masterDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function mergeDataExportConfigurationTransfers(
        DataExportConfigurationTransfer $masterDataExportConfigurationTransfer,
        DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
    ): DataExportConfigurationTransfer;

    /**
     * Specification:
     * - Adds properties from `$additionalDataExportConfigurationsTransfer` to `$dataExportActionConfigurationTransfer` if they are missing.
     * - Returns data export action configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportActionConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function resolveDataExportActionConfiguration(
        DataExportConfigurationTransfer $dataExportActionConfigurationTransfer,
        DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
    ): DataExportConfigurationTransfer;

    /**
     * Specification:
     * - Formats data from `DataExportBatchTransfer` to a format specified in `DataExportConfigurationTransfer`.
     * - Uses stack of `DataExportFormatterPluginInterface` plugins for formatting data.
     * - If applicable formatter plugin is not found will fallback to default `csv` formatter.
     * - Writes formatted data according to connection configuration in `DataExportConfigurationTransfer`.
     * - Uses stack of `DataExportConnectionPluginInterface` plugins for writing data.
     * - If applicable connection plugin is not found will fallback to default `local` connection.
     * - Returns `DataExportWriteResponseTransfer` with isSuccessful=true if data was written successfully.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    public function write(
        DataExportBatchTransfer $dataExportBatchTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportWriteResponseTransfer;

    /**
     * Specification:
     * - Returns file extension according to format configuration in DataExportConfigurationTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return string|null
     */
    public function getFormatExtension(DataExportConfigurationTransfer $dataExportConfigurationTransfer): ?string;
}
