<?php

namespace Spryker\Zed\SalesDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportResultDocumentTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderExpenseReader;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderItemReader;
use Spryker\Zed\SalesDataExport\Business\Writer\CsvWriter;
use Spryker\Zed\SalesDataExport\Business\Reader\OrderReader;

class OrderExpenseSequencialExporter
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
            $orderExpenses = $this->orderExpenseReader->sequencialRead($exportConfiguration, $offset, static::READ_BATCH_SIZE);

            if ($offset === 0 && count($orderExpenses)) {
                $this->dataExportService->write($exportConfiguration, ['mode' => 'w'], ['rows' => [array_keys($orderExpenses[0])]]);
            }

            list($destination, $objectCount) = $this->dataExportService->write($exportConfiguration, ['mode' => 'a'], ['rows' => $orderExpenses]);
            $result->addDocuments(
                (new DataExportResultDocumentTransfer())
                    ->setName($destination)
                    ->setObjectCount($objectCount)
            );

            $offset += count($orderExpenses);
        } while (count($orderExpenses) == static::READ_BATCH_SIZE);

        return $result;
    }
}
