<?php

namespace Spryker\Zed\SalesDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportResultDocumentTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\SalesDataExport\Business\Writer\CsvWriter;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderReader;

class OrderSequencialExporter
{
    /**
     * @var OrderReader
     */
    protected $orderReader;

    /**
     * @var DataExportService
     */
    protected $dataExportService;

    protected const READ_BATCH_SIZE = 100;

    /**
     * @param OrderReader $orderReader
     * @param DataExportService $dataExportService
     */
    public function __construct(OrderReader $orderReader, DataExportService $dataExportService)
    {
        $this->orderReader = $orderReader;
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
            $orders = $this->orderReader->sequencialRead($exportConfiguration, $offset, static::READ_BATCH_SIZE);

            if ($offset === 0 && count($orders)) {
                $this->dataExportService->write($exportConfiguration, ['mode' => 'w'], ['rows' => [array_keys($orders[0])]]);
            }

            list($destination, $objectCount) = $this->dataExportService->write($exportConfiguration, ['mode' => 'a'], ['rows' => $orders]);
            $result->addDocuments(
                (new DataExportResultDocumentTransfer())
                    ->setName($destination)
                    ->setObjectCount($objectCount)
            );

            $offset += count($orders);
        } while (count($orders) == static::READ_BATCH_SIZE);

        return $result;
    }
}
