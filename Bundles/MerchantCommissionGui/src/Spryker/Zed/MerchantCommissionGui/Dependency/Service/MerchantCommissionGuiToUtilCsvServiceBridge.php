<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Dependency\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MerchantCommissionGuiToUtilCsvServiceBridge implements MerchantCommissionGuiToUtilCsvServiceInterface
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
     * @return list<list<string>>
     */
    public function readUploadedFile(UploadedFile $file): array
    {
        return $this->utilCsvService->readUploadedFile($file);
    }
}
