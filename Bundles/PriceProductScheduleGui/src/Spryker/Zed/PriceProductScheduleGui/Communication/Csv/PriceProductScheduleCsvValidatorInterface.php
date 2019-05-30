<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Csv;

use Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PriceProductScheduleCsvValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $importCsv
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer
     */
    public function validateCsvFile(UploadedFile $importCsv): PriceProductScheduleCsvValidationResultTransfer;
}
