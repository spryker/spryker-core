<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Formatter;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class DataExportFormatter implements DataExportFormatterInterface
{
    protected const MESSAGE_FORMATTER_PLUGIN_NOT_FOUND = 'Formatter plugin not found for format "%s"';
    protected const DEFAULT_FORMAT_TYPE = 'csv';

    /**
     * @var \Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportFormatterPluginInterface[]
     */
    protected $dataExportFormatterPlugins;

    /**
     * @var \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface
     */
    protected $dataExportCsvFormatter;

    /**
     * @param \Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportFormatterPluginInterface[] $dataExportFormatterPlugins
     * @param \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface $dataExportCsvFormatter
     */
    public function __construct(array $dataExportFormatterPlugins, DataExportFormatterInterface $dataExportCsvFormatter)
    {
        $this->dataExportFormatterPlugins = $dataExportFormatterPlugins;
        $this->dataExportCsvFormatter = $dataExportCsvFormatter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportFormatResponseTransfer
     */
    public function formatBatch(
        DataExportBatchTransfer $dataExportBatchTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportFormatResponseTransfer {
        $dataExportConfigurationTransfer->requireFormat();

        foreach ($this->dataExportFormatterPlugins as $dataExportFormatterPlugin) {
            if (!$dataExportFormatterPlugin->isApplicable($dataExportConfigurationTransfer)) {
                continue;
            }

            return $dataExportFormatterPlugin->format($dataExportBatchTransfer, $dataExportConfigurationTransfer);
        }

        if ($dataExportConfigurationTransfer->getFormat()->getType() === static::DEFAULT_FORMAT_TYPE) {
            return $this->dataExportCsvFormatter->formatBatch($dataExportBatchTransfer, $dataExportConfigurationTransfer);
        }

        return $this->createFormatterNotFoundResponse($dataExportConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return string|null
     */
    public function getFormatExtension(DataExportConfigurationTransfer $dataExportConfigurationTransfer): ?string
    {
        foreach ($this->dataExportFormatterPlugins as $dataExportFormatterPlugin) {
            if (!$dataExportFormatterPlugin->isApplicable($dataExportConfigurationTransfer)) {
                continue;
            }

            return $dataExportFormatterPlugin->getExtension($dataExportConfigurationTransfer);
        }

        if ($dataExportConfigurationTransfer->getFormat()->getType() === static::DEFAULT_FORMAT_TYPE) {
            return $this->dataExportCsvFormatter->getFormatExtension($dataExportConfigurationTransfer);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportFormatResponseTransfer
     */
    protected function createFormatterNotFoundResponse(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportFormatResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())->setValue(
            sprintf(static::MESSAGE_FORMATTER_PLUGIN_NOT_FOUND, $dataExportConfigurationTransfer->getFormat()->getType())
        );

        return (new DataExportFormatResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
