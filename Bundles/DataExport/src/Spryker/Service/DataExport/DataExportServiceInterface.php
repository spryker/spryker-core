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
     * - Reads configurations from the fully qualified file name defined in $filePath.
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
     * - Merges values from secondary configuration into primary configuration without overriding values.
     * - Properties `DataExportConfigurationTransfer.hooks` and `DataExportConfigurationTransfer.filterCriteria` will be merged.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $primaryDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $secondaryDataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function mergeDataExportConfigurationTransfers(
        DataExportConfigurationTransfer $primaryDataExportConfigurationTransfer,
        DataExportConfigurationTransfer $secondaryDataExportConfigurationTransfer
    ): DataExportConfigurationTransfer;

    /**
     * Specification:
     * - Merges additional configuration's defaults on the provided action configuration.
     * - Merges values from additional configuration, based on matching "data_entity" name, into action configuration without overriding values.
     * - Returns merged action configuration.
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
     * - If no applicable formatter plugin is found, will fallback to default `csv` formatter.
     * - Writes formatted data according to connection configuration in `DataExportConfigurationTransfer`.
     * - Uses stack of `DataExportConnectionPluginInterface` plugins for writing data.
     * - If no applicable connection plugin is found, will fallback to default `local` connection.
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
