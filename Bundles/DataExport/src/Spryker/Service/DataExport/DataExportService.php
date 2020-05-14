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
use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Kernel\BundleConfigResolverAwareTrait;

/**
 * @method \Spryker\Service\DataExport\DataExportServiceFactory getFactory()
 * @method \Spryker\Service\DataExport\DataExportConfig getConfig()
 */
class DataExportService extends AbstractService implements DataExportServiceInterface
{
    use BundleConfigResolverAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $filePath
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function parseConfiguration(string $filePath): DataExportConfigurationsTransfer
    {
        return $this->getFactory()
            ->createDataExportConfigurationYamlParser()
            ->parseConfigurationFile($filePath);
    }

    /**
     * {@inheritDoc}
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
    ): DataExportConfigurationTransfer {
        return $this->getFactory()
            ->createDataExportConfigurationMerger()
            ->mergeDataExportConfigurationTransfers($masterDataExportConfigurationTransfer, $slaveDataExportConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function resolveDataExportActionConfiguration(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
    ): DataExportConfigurationTransfer {
        return $this->getFactory()
            ->createDataExportConfigurationResolver()
            ->resolveDataExportActionConfiguration($dataExportConfigurationTransfer, $additionalDataExportConfigurationsTransfer);
    }

    /**
     * {@inheritDoc}
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
    ): DataExportWriteResponseTransfer {
        return $this->getFactory()
            ->createDataExportWriter()
            ->write($dataExportBatchTransfer, $dataExportConfigurationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return string|null
     */
    public function getFormatExtension(DataExportConfigurationTransfer $dataExportConfigurationTransfer): ?string
    {
        return $this->getFactory()
            ->createDataExportFormatter()
            ->getFormatExtension($dataExportConfigurationTransfer);
    }
}
