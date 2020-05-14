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

class SalesDataExportToDataExportServiceBridge implements SalesDataExportToDataExportServiceInterface
{
    /**
     * @var \Spryker\Service\DataExport\DataExportServiceInterface
     */
    protected $dataExportService;

    /**
     * @param \Spryker\Service\DataExport\DataExportServiceInterface $dataExportService
     */
    public function __construct($dataExportService)
    {
        $this->dataExportService = $dataExportService;
    }

    /**
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function parseConfiguration(string $filePath): DataExportConfigurationsTransfer
    {
        return $this->dataExportService->parseConfiguration($filePath);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $masterDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function mergeDataExportConfigurationTransfers(
        DataExportConfigurationTransfer $masterDataExportConfigurationTransfer,
        DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
    ): DataExportConfigurationTransfer {
        return $this->dataExportService->mergeDataExportConfigurationTransfers(
            $masterDataExportConfigurationTransfer,
            $slaveDataExportConfigurationTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function resolveDataExportActionConfiguration(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
    ): DataExportConfigurationTransfer {
        return $this->dataExportService->resolveDataExportActionConfiguration(
            $dataExportConfigurationTransfer,
            $additionalDataExportConfigurationsTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    public function write(
        DataExportBatchTransfer $dataExportBatchTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportWriteResponseTransfer {
        return $this->dataExportService->write($dataExportBatchTransfer, $dataExportConfigurationTransfer);
    }
}
