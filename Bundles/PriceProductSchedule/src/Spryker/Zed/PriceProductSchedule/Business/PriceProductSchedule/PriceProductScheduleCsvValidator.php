<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer;
use Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile;
use Spryker\Zed\PriceProductSchedule\Dependency\Service\PriceProductScheduleToUtilCsvServiceInterface;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

class PriceProductScheduleCsvValidator implements PriceProductScheduleCsvValidatorInterface
{
    protected const ERROR_HEADERS_MISSING = '%s header(s) is missing in uploaded csv file';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Service\PriceProductScheduleToUtilCsvServiceInterface
     */
    protected $csvService;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected $priceProductScheduleConfig;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Service\PriceProductScheduleToUtilCsvServiceInterface $csvService
     * @param \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig $priceProductScheduleConfig
     */
    public function __construct(
        PriceProductScheduleToUtilCsvServiceInterface $csvService,
        PriceProductScheduleConfig $priceProductScheduleConfig
    ) {
        $this->csvService = $csvService;
        $this->priceProductScheduleConfig = $priceProductScheduleConfig;
    }

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile $uploadedFile
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer
     */
    public function validateCsvFile(UploadedFile $uploadedFile): PriceProductScheduleCsvValidationResultTransfer
    {
        $priceProductScheduleCsvValidationResultTransfer = (new PriceProductScheduleCsvValidationResultTransfer())
            ->setIsSuccess(false);
        $importItems = $this->csvService->readUploadedFile($uploadedFile);
        $headers = current($importItems);

        $expectedHeaders = $this->priceProductScheduleConfig->getFieldsList();

        $missedHeaders = array_diff($expectedHeaders, $headers);

        if (count($missedHeaders) === 0) {
            return $priceProductScheduleCsvValidationResultTransfer->setIsSuccess(true);
        }

        return $priceProductScheduleCsvValidationResultTransfer
            ->setError(sprintf(static::ERROR_HEADERS_MISSING, implode(', ', $missedHeaders)));
    }
}
