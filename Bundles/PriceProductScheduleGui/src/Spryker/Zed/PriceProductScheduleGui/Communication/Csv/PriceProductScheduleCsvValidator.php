<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Csv;

use Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface;
use Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PriceProductScheduleCsvValidator implements PriceProductScheduleCsvValidatorInterface
{
    protected const ERROR_HEADERS_MISSING = '%s header(s) is missing in uploaded csv file';
    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface
     */
    protected $csvService;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig
     */
    protected $priceProductScheduleGuiConfig;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface $csvService
     * @param \Spryker\Zed\PriceProductScheduleGui\PriceProductScheduleGuiConfig $priceProductScheduleGuiConfig
     */
    public function __construct(
        PriceProductScheduleGuiToUtilCsvServiceInterface $csvService,
        PriceProductScheduleGuiConfig $priceProductScheduleGuiConfig
    ) {
        $this->csvService = $csvService;
        $this->priceProductScheduleGuiConfig = $priceProductScheduleGuiConfig;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $importCsv
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer
     */
    public function validateCsvFile(UploadedFile $importCsv): PriceProductScheduleCsvValidationResultTransfer
    {
        $priceProductScheduleCsvValidationResultTransfer = (new PriceProductScheduleCsvValidationResultTransfer())
            ->setIsSuccess(false);
        $importItems = $this->csvService->readUploadedFile($importCsv);
        $headers = current($importItems);

        $expectedHeaders = $this->priceProductScheduleGuiConfig->getFieldsList();

        $missedHeaders = array_diff($expectedHeaders, $headers);

        if (count($missedHeaders) === 0) {
            return $priceProductScheduleCsvValidationResultTransfer->setIsSuccess(true);
        }

        return $priceProductScheduleCsvValidationResultTransfer
            ->setError(sprintf(static::ERROR_HEADERS_MISSING, implode(', ', $missedHeaders)));
    }
}
