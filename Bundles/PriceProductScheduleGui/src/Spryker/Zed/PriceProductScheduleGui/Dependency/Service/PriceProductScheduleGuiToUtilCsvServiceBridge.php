<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class PriceProductScheduleGuiToUtilCsvServiceBridge implements PriceProductScheduleGuiToUtilCsvServiceInterface
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
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return array
     */
    public function readUploadedFile(UploadedFile $file): array
    {
        return $this->utilCsvService->readUploadedFile($file);
    }
}
