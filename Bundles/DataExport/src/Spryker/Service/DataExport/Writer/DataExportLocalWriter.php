<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Writer;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportLocalWriteConfigurationTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Service\DataExport\Formatter\DataExportFormatterInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class DataExportLocalWriter implements DataExportWriterInterface
{
    protected const MESSAGE_INVALID_WRITE_CONFIGURATION = 'Expected write configuration of type "%s", "%s" given.';
    protected const MESSAGE_WRITE_FAIL = 'Failed to write file "%s".';

    protected const ACCESS_MODE_DEFAULT = 'wb';

    /**
     * @var \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface
     */
    protected $dataExportFormatter;

    /**
     * @param \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface $dataExportFormatter
     */
    public function __construct(DataExportFormatterInterface $dataExportFormatter)
    {
        $this->dataExportFormatter = $dataExportFormatter;
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
        $dataExportWriteResponseTransfer = (new DataExportWriteResponseTransfer())
            ->setIsSuccessful(false);

        if (!$writeConfiguration instanceof DataExportLocalWriteConfigurationTransfer) {
            return $dataExportWriteResponseTransfer->addMessage(
                $this->createInvalidWriteConfigurationErrorMessage($writeConfiguration)
            );
        }

        $dataFormatResponseTransfer = $this->dataExportFormatter->formatBatch($data, $dataExportConfigurationTransfer);
        if (!$dataFormatResponseTransfer->getIsSuccessful()) {
            return $dataExportWriteResponseTransfer
                ->setMessages($dataFormatResponseTransfer->getMessages());
        }

        $filePath = $dataExportConfigurationTransfer->getDestination();
        if (!$this->createDirectory($filePath)) {
            return $dataExportWriteResponseTransfer->addMessage($this->createWriteFailErrorMessage($filePath));
        }

        $file = fopen($filePath, $writeConfiguration->getMode() ?? static::ACCESS_MODE_DEFAULT);
        $result = fwrite($file, $dataFormatResponseTransfer->getDataFormatted());
        if ($result === false) {
            return $dataExportWriteResponseTransfer->addMessage($this->createWriteFailErrorMessage($filePath));
        }
        fclose($file);

        return $dataExportWriteResponseTransfer->setIsSuccessful(true);
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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $writeConfiguration
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createInvalidWriteConfigurationErrorMessage(AbstractTransfer $writeConfiguration): MessageTransfer
    {
        return (new MessageTransfer())->setValue(sprintf(
            static::MESSAGE_INVALID_WRITE_CONFIGURATION,
            DataExportWriteResponseTransfer::class,
            get_class($writeConfiguration)
        ));
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
