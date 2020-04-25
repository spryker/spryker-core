<?php

namespace Spryker\Zed\SalesDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportResultDocumentTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderItemReader;
use Spryker\Zed\SalesDataExport\Business\Writer\CsvWriter;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderReader;

class OrderItemSequencialExporter
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
            $orderItems = $this->orderItemReader->sequencialRead($exportConfiguration, $offset, static::READ_BATCH_SIZE);

            if ($offset === 0 && count($orderItems)) {
                $this->dataExportService->write($exportConfiguration, ['mode' => 'w'], ['rows' => [array_keys($orderItems[0])]]);
            }

            list($destination, $objectCount) = $this->dataExportService->write($exportConfiguration, ['mode' => 'a'], ['rows' => $orderItems]);
            $result->addDocuments(
                (new DataExportResultDocumentTransfer())
                    ->setName($destination)
                    ->setObjectCount($objectCount)
            );

            $offset += count($orderItems);
        } while (count($orderItems) == static::READ_BATCH_SIZE);

        return $result;
    }
}
