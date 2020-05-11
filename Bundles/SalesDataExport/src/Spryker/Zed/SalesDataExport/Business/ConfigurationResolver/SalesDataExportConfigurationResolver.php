<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business\ConfigurationResolver;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface;
use Spryker\Zed\SalesDataExport\SalesDataExportConfig;

class SalesDataExportConfigurationResolver implements SalesDataExportConfigurationResolverInterface
{
    /**
     * @var \Spryker\Zed\SalesDataExport\SalesDataExportConfig
     */
    protected $salesDataExportConfig;

    /**
     * @var \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface
     */
    protected $dataExportService;

    /**
     * @param \Spryker\Zed\SalesDataExport\SalesDataExportConfig $salesDataExportConfig
     * @param \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface $dataExportService
     */
    public function __construct(
        SalesDataExportConfig $salesDataExportConfig,
        SalesDataExportToDataExportServiceInterface $dataExportService
    ) {
        $this->salesDataExportConfig = $salesDataExportConfig;
        $this->dataExportService = $dataExportService;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function resolveSalesDataExportActionConfiguration(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportConfigurationTransfer
    {
        $salesDataExportDataExportConfigurationsTransfer = $this->dataExportService->parseConfiguration(
            $this->salesDataExportConfig->getDefaultExportConfigurationPath()
        );

        $dataExportConfigurationTransfer = $this->dataExportService->mergeDataExportConfigurations(
            $dataExportConfigurationTransfer,
            $salesDataExportDataExportConfigurationsTransfer->getDefaults()
        );

        $salesDataExportActionConfigurationTransfer = $this->findSalesDataExportActionConfiguration(
            $dataExportConfigurationTransfer->getDataEntity(),
            $salesDataExportDataExportConfigurationsTransfer
        );

        if (!$salesDataExportActionConfigurationTransfer) {
            return $dataExportConfigurationTransfer;
        }

        return $this->dataExportService->mergeDataExportConfigurations(
            $dataExportConfigurationTransfer,
            $salesDataExportActionConfigurationTransfer
        );
    }

    /**
     * @param string $dataEntity
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer|null
     */
    protected function findSalesDataExportActionConfiguration(
        string $dataEntity,
        DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
    ): ?DataExportConfigurationTransfer {
        foreach ($dataExportConfigurationsTransfer->getActions() as $dataExportConfigurationTransfer) {
            if ($dataExportConfigurationTransfer->getDataEntity() === $dataEntity) {
                return $dataExportConfigurationTransfer;
            }
        }

        return null;
    }
}
