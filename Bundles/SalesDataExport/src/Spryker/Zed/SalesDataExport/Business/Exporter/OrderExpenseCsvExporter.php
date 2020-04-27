<?php

namespace Spryker\Zed\SalesDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportResultDocumentTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderExpenseReader;

class OrderExpenseCsvExporter
{
    /**
     * @var OrderExpenseReader
     */
    protected $orderExpenseReader;

    /**
     * @var DataExportService
     */
    protected $dataExportService;

    protected const READ_BATCH_SIZE = 100;

    /**
     * @param OrderExpenseReader $orderExpenseReader
     * @param DataExportService $dataExportService
     */
    public function __construct(OrderExpenseReader $orderExpenseReader, DataExportService $dataExportService)
    {
        $this->orderExpenseReader = $orderExpenseReader;
        $this->dataExportService = $dataExportService;
    }

    /**
     * @param array $exportConfiguration
     *
     * @return array
     */
    public function exportBatch(array $exportConfiguration): DataExportResultTransfer
    {
        $result = new DataExportResultTransfer();

        $offset = 0;
        do {
            list($headers, $rows) = $this->orderExpenseReader->csvReadBatch($exportConfiguration, $offset, static::READ_BATCH_SIZE);

            list($dataExportResultDocumentTransfer) = $this->dataExportService->writeBatch(
                $exportConfiguration,
                ['mode' => $offset === 0 ? 'w' : 'a'],
                ['headers' => $headers, 'rows' => $rows]
            );

            $result->addDocuments($dataExportResultDocumentTransfer);

            $offset += count($rows);
        } while (count($rows) == static::READ_BATCH_SIZE);

        return $result;
    }
}
