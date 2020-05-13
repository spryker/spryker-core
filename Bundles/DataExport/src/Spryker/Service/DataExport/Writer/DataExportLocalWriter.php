<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Writer;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Service\DataExport\DataExportConfig;
use Spryker\Service\DataExport\Formatter\DataExportFormatterInterface;

class DataExportLocalWriter implements DataExportWriterInterface
{
    protected const MESSAGE_WRITE_FAIL = 'Failed to write file "%s".';

    protected const ACCESS_MODE_TYPE_OVERWRITE = 'wb';
    protected const ACCESS_MODE_TYPE_APPEND = 'ab';

    /**
     * @var \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface
     */
    protected $dataExportFormatter;

    /**
     * @var \Spryker\Service\DataExport\DataExportConfig
     */
    protected $dataExportConfig;

    /**
     * @param \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface $dataExportFormatter
     * @param \Spryker\Service\DataExport\DataExportConfig $dataExportConfig
     */
    public function __construct(DataExportFormatterInterface $dataExportFormatter, DataExportConfig $dataExportConfig)
    {
        $this->dataExportFormatter = $dataExportFormatter;
        $this->dataExportConfig = $dataExportConfig;
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
        $dataExportWriteResponseTransfer = (new DataExportWriteResponseTransfer())
            ->setIsSuccessful(false);

        $dataFormatResponseTransfer = $this->dataExportFormatter->formatBatch($dataExportBatchTransfer, $dataExportConfigurationTransfer);
        if (!$dataFormatResponseTransfer->getIsSuccessful()) {
            return $dataExportWriteResponseTransfer
                ->setMessages($dataFormatResponseTransfer->getMessages());
        }

        $filePath = $this->dataExportConfig->getDataExportDefaultLocalPath() . DIRECTORY_SEPARATOR . $dataExportConfigurationTransfer->getDestination();
        if (!$this->createDirectory($filePath)) {
            return $dataExportWriteResponseTransfer->addMessage($this->createWriteFailErrorMessage($filePath));
        }

        $file = fopen($filePath, $dataExportBatchTransfer->getOffset() === 0 ? static::ACCESS_MODE_TYPE_OVERWRITE : static::ACCESS_MODE_TYPE_APPEND);
        $result = fwrite($file, $dataFormatResponseTransfer->getDataFormatted());
        if ($result === false) {
            return $dataExportWriteResponseTransfer->addMessage($this->createWriteFailErrorMessage($filePath));
        }
        fclose($file);

        return $dataExportWriteResponseTransfer
            ->setFilename(basename($filePath))
            ->setIsSuccessful(true);
    }

    /**
     * @param string $filePath
     * @param int $permission
     *
     * @return bool
     */
    protected function createDirectory(string $filePath, int $permission = 0777): bool
    {
        $dirName = dirname($filePath);

        return is_dir($dirName) || mkdir($dirName, $permission, true) || is_dir($dirName);
    }

    /**
     * @param string|null $filePath
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createWriteFailErrorMessage(?string $filePath): MessageTransfer
    {
        return (new MessageTransfer())->setValue(sprintf(static::MESSAGE_WRITE_FAIL, $filePath));
    }
}
