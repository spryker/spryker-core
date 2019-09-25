<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilCsv;

use Generated\Shared\Transfer\CsvFileTransfer;
use Spryker\Service\Kernel\AbstractService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \Spryker\Service\UtilCsv\UtilCsvServiceFactory getFactory()
 */
class UtilCsvService extends AbstractService implements UtilCsvServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string[][]
     */
    public function readUploadedFile(UploadedFile $file): array
    {
        return $this->getFactory()
            ->createFileReader()
            ->readFile($file);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportFile(CsvFileTransfer $csvFileTransfer): StreamedResponse
    {
        return $this->getFactory()
            ->createFileExporter()
            ->exportFile($csvFileTransfer);
    }
}
