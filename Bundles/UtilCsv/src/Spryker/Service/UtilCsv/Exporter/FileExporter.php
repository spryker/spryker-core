<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilCsv\Exporter;

use Generated\Shared\Transfer\CsvFileTransfer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileExporter implements FileExporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportFile(CsvFileTransfer $csvFileTransfer): StreamedResponse
    {
        $csvFileTransfer->requireFileName();
        if (!$csvFileTransfer->getDataGenerators()) {
            $csvFileTransfer->requireData();
        }

        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($csvFileTransfer) {
            /** @var resource $csvHandle */
            $csvHandle = fopen('php://output', 'w+');

            $this->writeCsvHeader($csvFileTransfer, $csvHandle);
            $this->writeCsvRow($csvFileTransfer, $csvHandle);
            $this->writeCsvRowByDataGenerators($csvFileTransfer, $csvHandle);

            fclose($csvHandle);
        });

        $this->configureResponseHeaders($streamedResponse, $csvFileTransfer);

        return $streamedResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     * @param resource $csvHandle
     *
     * @return void
     */
    protected function writeCsvHeader(CsvFileTransfer $csvFileTransfer, $csvHandle): void
    {
        if ($csvFileTransfer->getHeader() && is_array($csvFileTransfer->getHeader())) {
            fputcsv($csvHandle, $csvFileTransfer->getHeader());
        }
    }

    /**
     * @deprecated Use {@link \Spryker\Service\UtilCsv\Exporter\FileExporter::writeCsvRowByDataGenerators()} instead.
     *
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     * @param resource $csvHandle
     *
     * @return void
     */
    protected function writeCsvRow(CsvFileTransfer $csvFileTransfer, $csvHandle): void
    {
        foreach ($csvFileTransfer->getData() as $csvLineArray) {
            fputcsv($csvHandle, $csvLineArray);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     * @param resource $csvHandle
     *
     * @return void
     */
    protected function writeCsvRowByDataGenerators(CsvFileTransfer $csvFileTransfer, $csvHandle): void
    {
        foreach ($csvFileTransfer->getDataGenerators() as $dataGenerator) {
            foreach ($dataGenerator as $data) {
                fputcsv($csvHandle, $data);
            }
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\StreamedResponse $response
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     *
     * @return void
     */
    protected function configureResponseHeaders(StreamedResponse $response, CsvFileTransfer $csvFileTransfer): void
    {
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $csvFileTransfer->getFileName() . '"');
    }
}
