<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;

class MerchantSalesOrderDataExportToDataExportServiceBridge implements MerchantSalesOrderDataExportToDataExportServiceInterface
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
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportActionConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function resolveDataExportActionConfiguration(
        DataExportConfigurationTransfer $dataExportActionConfigurationTransfer,
        DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
    ): DataExportConfigurationTransfer {
        return $this->dataExportService->resolveDataExportActionConfiguration(
            $dataExportActionConfigurationTransfer,
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
