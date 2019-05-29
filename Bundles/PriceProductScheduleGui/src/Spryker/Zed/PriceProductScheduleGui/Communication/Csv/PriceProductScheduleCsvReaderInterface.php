<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Csv;

use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PriceProductScheduleCsvReaderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $importCsv
     * @param \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer
     */
    public function readPriceProductScheduleImportTransfersFromCsvFile(
        UploadedFile $importCsv,
        PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
    ): PriceProductScheduledListImportRequestTransfer;
}
