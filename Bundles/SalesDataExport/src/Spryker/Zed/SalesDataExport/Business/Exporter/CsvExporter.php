<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportLocalWriteConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolverInterface;
use Spryker\Zed\SalesDataExport\Business\Reader\CsvReaderInterface;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface;
use Spryker\Zed\SalesDataExport\SalesDataExportConfig;

class CsvExporter implements CsvExporterInterface
{
    /**
     * @uses \Spryker\Service\DataExport\Writer\DataExportLocalWriter::ACCESS_MODE_TYPE_OVERWRITE
     */
    protected const ACCESS_MODE_TYPE_OVERWRITE = 'wb';

    /**
     * @uses \Spryker\Service\DataExport\Writer\DataExportLocalWriter::ACCESS_MODE_TYPE_APPEND
     */
    protected const ACCESS_MODE_TYPE_APPEND = 'ab';

    protected const READ_BATCH_SIZE = 100;

    /**
     * @var \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface
     */
    protected $dataExportService;

    /**
     * @var \Spryker\Zed\SalesDataExport\SalesDataExportConfig
     */
    protected $salesDataExportConfig;

    /**
     * @var \Spryker\Zed\SalesDataExport\Business\Reader\CsvReaderInterface
     */
    protected $csvReader;

    /**
     * @var \Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolverInterface
     */
    protected $dataExportConfigurationResolver;

    /**
     * @param \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface $dataExportService
     * @param \Spryker\Zed\SalesDataExport\SalesDataExportConfig $salesDataExportConfig
     * @param \Spryker\Zed\SalesDataExport\Business\Reader\CsvReaderInterface $csvReader
     * @param \Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolverInterface $dataExportConfigurationResolver
     */
    public function __construct(
        SalesDataExportToDataExportServiceInterface $dataExportService,
        SalesDataExportConfig $salesDataExportConfig,
        CsvReaderInterface $csvReader,
        SalesDataExportConfigurationResolverInterface $dataExportConfigurationResolver
    ) {
        $this->dataExportService = $dataExportService;
        $this->salesDataExportConfig = $salesDataExportConfig;
        $this->csvReader = $csvReader;
        $this->dataExportConfigurationResolver = $dataExportConfigurationResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function export(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        $dataExportConfigurationTransfer = $this->dataExportConfigurationResolver->resolveSalesDataExportActionConfiguration($dataExportConfigurationTransfer);

        $dataExportResultTransfer = (new DataExportResultTransfer())
            ->setDataEntity($dataExportConfigurationTransfer->getDataEntity())
            ->setIsSuccessful(false);

        $readBatchSize = static::READ_BATCH_SIZE;

        $offset = 0;
        do {
            $csvData = $this->csvReader->csvReadBatch($dataExportConfigurationTransfer, $offset, $readBatchSize);

            $dataExportWriteResponseTransfer = $this->dataExportService->write(
                $csvData,
                $dataExportConfigurationTransfer,
                $this->createDataExportLocalWriteConfiguration($offset)
            );

            if (!$dataExportWriteResponseTransfer->getIsSuccessful()) {
                $dataExportResultTransfer
                    ->fromArray($dataExportWriteResponseTransfer->toArray(), true)
                    ->setExportCount($offset);

                return $this->createDataExportReportTransfer($dataExportResultTransfer);
            }

            $exportedRowsCount = count($csvData);
            $offset += $exportedRowsCount;

            $dataExportResultTransfer
                ->setIsSuccessful(true)
                ->setExportCount($offset - 1)
                ->setFileName($dataExportWriteResponseTransfer->getFilename());
        } while ($exportedRowsCount === $readBatchSize);

        return $this->createDataExportReportTransfer($dataExportResultTransfer);
    }

    /**
     * @param int $offset
     *
     * @return \Generated\Shared\Transfer\DataExportLocalWriteConfigurationTransfer
     */
    protected function createDataExportLocalWriteConfiguration(int $offset): DataExportLocalWriteConfigurationTransfer
    {
        $writeMode = $offset === 0
            ? static::ACCESS_MODE_TYPE_OVERWRITE
            : static::ACCESS_MODE_TYPE_APPEND;

        return (new DataExportLocalWriteConfigurationTransfer())
            ->setMode($writeMode);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportResultTransfer $dataExportResultTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    protected function createDataExportReportTransfer(DataExportResultTransfer $dataExportResultTransfer): DataExportReportTransfer
    {
        return (new DataExportReportTransfer())
            ->setIsSuccessful($dataExportResultTransfer->getIsSuccessful())
            ->addDataExportResult($dataExportResultTransfer);
    }
}
