<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilCsv\Exporter;

use Generated\Shared\Transfer\CsvFileTransfer;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface FileExporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CsvFileTransfer $csvFileTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportFile(CsvFileTransfer $csvFileTransfer): StreamedResponse;
}
