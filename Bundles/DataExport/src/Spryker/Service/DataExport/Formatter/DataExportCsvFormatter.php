<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Formatter;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Service\DataExport\Dependency\External\DataExportToCsvWriterInterface;

class DataExportCsvFormatter implements DataExportFormatterInterface
{
    protected const MESSAGE_INVALID_DATA_SET = 'Invalid data set provided.';

    protected const EXTENSION_CSV = 'csv';

    /**
     * @var \Spryker\Service\DataExport\Dependency\External\DataExportToCsvWriterInterface
     */
    protected $csvWriter;

    /**
     * @param \Spryker\Service\DataExport\Dependency\External\DataExportToCsvWriterInterface $csvWriter
     */
    public function __construct(DataExportToCsvWriterInterface $csvWriter)
    {
        $this->csvWriter = $csvWriter;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportFormatResponseTransfer
     */
    public function formatBatch(array $data, DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportFormatResponseTransfer
    {
        $dataExportFormatResponseTransfer = (new DataExportFormatResponseTransfer())->setIsSuccessful(false);

        foreach ($data as $row) {
            if (!is_array($row)) {
                return $dataExportFormatResponseTransfer->addMessage($this->createInvalidDataSetResponseMessage());
            }
            $this->csvWriter->insertOne($row);
        }

        return $dataExportFormatResponseTransfer
            ->setIsSuccessful(true)
            ->setDataFormatted($this->csvWriter->getContent());
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createInvalidDataSetResponseMessage(): MessageTransfer
    {
        return (new MessageTransfer())->setValue(static::MESSAGE_INVALID_DATA_SET);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return string|null
     */
    public function getFormatExtension(DataExportConfigurationTransfer $dataExportConfigurationTransfer): ?string
    {
        return static::EXTENSION_CSV;
    }
}
