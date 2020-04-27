<?php

namespace Spryker\Zed\SalesDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportResultDocumentTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderItemReader;
use Spryker\Zed\SalesDataExport\Business\Writer\CsvWriter;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderReader;

class OrderItemCsvExporter
{
    /**
     * @var OrderItemReader
     */
    protected $orderItemReader;

    /**
     * @var DataExportService
     */
    protected $dataExportService;

    protected const READ_BATCH_SIZE = 100;

    /**
     * @param OrderItemReader $orderItemReader
     * @param DataExportService $dataExportService
     */
    public function __construct(OrderItemReader $orderItemReader, DataExportService $dataExportService)
    {
        $this->orderItemReader = $orderItemReader;
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
            list($headers, $rows) = $this->orderItemReader->csvReadBatch($exportConfiguration, $offset, static::READ_BATCH_SIZE);

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
