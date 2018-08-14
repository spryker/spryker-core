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
        $csvFileTransfer->requireData();

        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function () use ($csvFileTransfer) {
            $csvHandle = fopen('php://output', 'w+');
            if ($csvFileTransfer->getHeader() && is_array($csvFileTransfer->getHeader())) {
                fputcsv($csvHandle, $csvFileTransfer->getHeader());
            }
            foreach ($csvFileTransfer->getData() as $csvLineArray) {
                fputcsv($csvHandle, $csvLineArray);
            }
            fclose($csvHandle);
        });

        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="' . $csvFileTransfer->getFileName() . '"');

        return $streamedResponse;
    }
}
