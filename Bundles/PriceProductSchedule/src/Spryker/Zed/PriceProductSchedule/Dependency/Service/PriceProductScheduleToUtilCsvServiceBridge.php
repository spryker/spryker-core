<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Dependency\Service;

use Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile;

class PriceProductScheduleToUtilCsvServiceBridge implements PriceProductScheduleToUtilCsvServiceInterface
{
    /**
     * @var \Spryker\Service\UtilCsv\UtilCsvServiceInterface
     */
    protected $utilCsvService;

    /**
     * @param \Spryker\Service\UtilCsv\UtilCsvServiceInterface $utilCsvService
     */
    public function __construct($utilCsvService)
    {
        $this->utilCsvService = $utilCsvService;
    }

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile $fileUploadTransfer
     *
     * @return array
     */
    public function readUploadedFile(UploadedFile $fileUploadTransfer): array
    {
        return $this->utilCsvService->readUploadedFile($fileUploadTransfer);
    }
}
