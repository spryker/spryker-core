<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilCsv;

use Generated\Shared\Transfer\CsvFileTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface UtilCsvServiceInterface
{
    /**
     * Specification:
     * - Read data from uploaded csv file and returns content
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return array
     */
    public function readFile(UploadedFile $file): array;

    /**
     * Specification:
     * - Generates csv file and returns response with file for export
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportFile(CsvFileTransfer $csvFileTransfer): StreamedResponse;
}
