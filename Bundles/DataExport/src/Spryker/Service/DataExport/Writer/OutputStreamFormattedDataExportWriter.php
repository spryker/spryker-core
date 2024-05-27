<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Writer;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatResponseTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class OutputStreamFormattedDataExportWriter implements FormattedDataExportWriterInterface
{
    /**
     * @var string
     */
    protected const ACCESS_MODE_TYPE_WRITE = 'w';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STREAM_OPEN_FAIL = 'Failed to open stream for destination: %s. Error: %s';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_WRITE_FAIL = 'Failed to write to stream for destination: %s. Error: %s';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_UNKNOWN_ERROR = 'Unknown error';

    /**
     * @var string
     */
    protected const ERROR_KEY_MESSAGE = 'message';

    /**
     * @param \Generated\Shared\Transfer\DataExportFormatResponseTransfer $dataExportFormatResponseTransfer
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    public function write(
        DataExportFormatResponseTransfer $dataExportFormatResponseTransfer,
        DataExportBatchTransfer $dataExportBatchTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportWriteResponseTransfer {
        $dataExportWriteResponseTransfer = (new DataExportWriteResponseTransfer())->setIsSuccessful(true);

        $streamResource = fopen($dataExportConfigurationTransfer->getDestinationOrFail(), static::ACCESS_MODE_TYPE_WRITE);
        if (!$streamResource) {
            $errorMessage = $this->getErrorMessage(
                static::ERROR_MESSAGE_STREAM_OPEN_FAIL,
                $dataExportConfigurationTransfer->getDestinationOrFail(),
            );

            return $dataExportWriteResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage((new MessageTransfer())->setValue($errorMessage));
        }

        $numberOfBytesWritten = fwrite($streamResource, $dataExportFormatResponseTransfer->getDataFormattedOrFail());
        if ($numberOfBytesWritten === false) {
            $errorMessage = $this->getErrorMessage(
                static::ERROR_MESSAGE_WRITE_FAIL,
                $dataExportConfigurationTransfer->getDestinationOrFail(),
            );

            return $dataExportWriteResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage((new MessageTransfer())->setValue($errorMessage));
        }
        fclose($streamResource);

        return $dataExportWriteResponseTransfer;
    }

    /**
     * @param string $messageTemplate
     * @param string $destination
     *
     * @return string
     */
    protected function getErrorMessage(string $messageTemplate, string $destination): string
    {
        $error = error_get_last();
        $errorMessage = $error ? $error[static::ERROR_KEY_MESSAGE] : static::ERROR_MESSAGE_UNKNOWN_ERROR;

        return sprintf($messageTemplate, $destination, $errorMessage);
    }
}
