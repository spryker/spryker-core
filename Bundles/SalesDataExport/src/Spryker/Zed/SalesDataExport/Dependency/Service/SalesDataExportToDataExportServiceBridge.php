<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Dependency\Service;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

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
     * @param array $data
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $writeConfiguration
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    public function write(
        array $data,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        AbstractTransfer $writeConfiguration
    ): DataExportWriteResponseTransfer {
        return $this->dataExportService->write($data, $dataExportConfigurationTransfer, $writeConfiguration);
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
    public function mergeDataExportConfigurations(
        DataExportConfigurationTransfer $masterDataExportConfigurationTransfer,
        DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
    ): DataExportConfigurationTransfer {
        return $this->dataExportService->mergeDataExportConfigurations($masterDataExportConfigurationTransfer, $slaveDataExportConfigurationTransfer);
    }
}
