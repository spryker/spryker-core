<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Dependency\Service;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;

interface SalesDataExportToDataExportServiceInterface
{
    /**
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
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function parseConfiguration(string $filePath): DataExportConfigurationsTransfer;

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $masterDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function mergeDataExportConfigurationTransfers(
        DataExportConfigurationTransfer $masterDataExportConfigurationTransfer,
        DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
    ): DataExportConfigurationTransfer;
}
