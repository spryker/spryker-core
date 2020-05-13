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
use Spryker\Service\DataExport\Dependency\External\DataExportToCsvFormatterInterface;

class DataExportCsvFormatter implements DataExportFormatterInterface
{
    protected const MESSAGE_INVALID_DATA_SET = 'Invalid data set provided.';

    protected const EXTENSION_CSV = 'csv';

    /**
     * @var \Spryker\Service\DataExport\Dependency\External\DataExportToCsvFormatterInterface
     */
    protected $csvFormatter;

    /**
     * @param \Spryker\Service\DataExport\Dependency\External\DataExportToCsvFormatterInterface $csvFormatter
     */
    public function __construct(DataExportToCsvFormatterInterface $csvFormatter)
    {
        $this->csvFormatter = $csvFormatter;
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
        $dataExportFormatResponseTransfer = (new DataExportFormatResponseTransfer())->setIsSuccessful(false);

        if ($dataExportBatchTransfer->getOffset() === 0) {
            $this->csvFormatter->addRecord($dataExportBatchTransfer->getFields());
        }

        foreach ($dataExportBatchTransfer->getData() as $row) {
            if (!is_array($row)) {
                return $dataExportFormatResponseTransfer->addMessage($this->createInvalidDataSetResponseMessage());
            }
            $this->csvFormatter->addRecord($this->filterOutNewlines($row));
        }

        return $dataExportFormatResponseTransfer
            ->setIsSuccessful(true)
            ->setDataFormatted($this->csvFormatter->getFormattedRecords());
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

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createInvalidDataSetResponseMessage(): MessageTransfer
    {
        return (new MessageTransfer())->setValue(static::MESSAGE_INVALID_DATA_SET);
    }

    /**
     * @param string[] $rowData
     *
     * @return string[]
     */
    protected function filterOutNewlines(array $rowData): array
    {
        return str_replace(["\n", "\r"], '', $rowData);
    }
}
