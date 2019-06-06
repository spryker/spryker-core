<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\File\UploadedFile;

interface PriceProductScheduleCsvValidatorInterface
{
    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\File\UploadedFile $uploadedFile
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer
     */
    public function validateCsvFile(UploadedFile $uploadedFile): PriceProductScheduleCsvValidationResultTransfer;
}
