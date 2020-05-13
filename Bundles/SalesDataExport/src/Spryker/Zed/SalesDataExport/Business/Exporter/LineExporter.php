<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;
use Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolverInterface;
use Spryker\Zed\SalesDataExport\Business\Reader\LineReaderInterface;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface;
use Spryker\Zed\SalesDataExport\SalesDataExportConfig;

class LineExporter implements ExporterInterface
{
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
     * @var \Spryker\Zed\SalesDataExport\Business\Reader\LineReaderInterface
     */
    protected $lineReader;

    /**
     * @var \Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolverInterface
     */
    protected $dataExportConfigurationResolver;

    /**
     * @param \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface $dataExportService
     * @param \Spryker\Zed\SalesDataExport\SalesDataExportConfig $salesDataExportConfig
     * @param \Spryker\Zed\SalesDataExport\Business\Reader\LineReaderInterface $lineReader
     * @param \Spryker\Zed\SalesDataExport\Business\ConfigurationResolver\SalesDataExportConfigurationResolverInterface $dataExportConfigurationResolver
     */
    public function __construct(
        SalesDataExportToDataExportServiceInterface $dataExportService,
        SalesDataExportConfig $salesDataExportConfig,
        LineReaderInterface $lineReader,
        SalesDataExportConfigurationResolverInterface $dataExportConfigurationResolver
    ) {
        $this->dataExportService = $dataExportService;
        $this->salesDataExportConfig = $salesDataExportConfig;
        $this->lineReader = $lineReader;
        $this->dataExportConfigurationResolver = $dataExportConfigurationResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function export(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        // TODO - move this into infrastructure
        $dataExportConfigurationTransfer = $this->dataExportConfigurationResolver->resolveSalesDataExportActionConfiguration($dataExportConfigurationTransfer);

        $dataExportResultTransfer = (new DataExportResultTransfer())
            ->setDataEntity($dataExportConfigurationTransfer->getDataEntity())
            ->setIsSuccessful(false);

        $readBatchSize = static::READ_BATCH_SIZE;

        $offset = 0;
        do {
            $dataBatch = $this->lineReader->lineReadBatch($dataExportConfigurationTransfer, $offset, $readBatchSize);

            $dataExportWriteResponseTransfer = $this->dataExportService->write(
                (new DataExportBatchTransfer())
                    ->setOffset($offset)
                    ->setFields(array_keys($dataBatch[0] ?? []))
                    ->setData($dataBatch),
                $dataExportConfigurationTransfer
            );

            if (!$dataExportWriteResponseTransfer->getIsSuccessful()) {
                $dataExportResultTransfer
                    ->fromArray($dataExportWriteResponseTransfer->toArray(), true)
                    ->setExportCount($offset);

                return $this->createDataExportReportTransfer($dataExportResultTransfer);
            }

            $exportedRowsCount = count($dataBatch);
            $offset += $exportedRowsCount;

            $dataExportResultTransfer
                ->setIsSuccessful(true)
                ->setExportCount($offset)
                ->setFileName($dataExportWriteResponseTransfer->getFilename());
        } while ($exportedRowsCount === $readBatchSize);

        return $this->createDataExportReportTransfer($dataExportResultTransfer);
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
