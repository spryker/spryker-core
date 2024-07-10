<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceInterface;
use Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MerchantCommissionCsvValidator implements MerchantCommissionCsvValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_HEADERS_MISSING = 'The following headers are missing in the uploaded CSV file: %s.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_EMPTY_COLUMNS = 'The following columns are empty in the uploaded CSV file: %s.';

    /**
     * @var list<string>
     */
    protected const REQUIRED_COLUMNS = [
        'key',
        'group',
        'priority',
        'stores',
    ];

    /**
     * @var \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig
     */
    protected MerchantCommissionGuiConfig $merchantCommissionGuiConfig;

    /**
     * @var \Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceInterface
     */
    protected MerchantCommissionGuiToUtilCsvServiceInterface $utilCsvService;

    /**
     * @param \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig $merchantCommissionGuiConfig
     * @param \Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceInterface $utilCsvService
     */
    public function __construct(
        MerchantCommissionGuiConfig $merchantCommissionGuiConfig,
        MerchantCommissionGuiToUtilCsvServiceInterface $utilCsvService
    ) {
        $this->merchantCommissionGuiConfig = $merchantCommissionGuiConfig;
        $this->utilCsvService = $utilCsvService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateMerchantCommissionCsvFile(UploadedFile $uploadedFile): ArrayObject
    {
        $importData = $this->utilCsvService->readUploadedFile($uploadedFile);

        $errorTransfers = $this->assertRequiredHeaders($importData, new ArrayObject());
        if ($errorTransfers->count() > 0) {
            return $errorTransfers;
        }

        return $this->assertRequiredColumns($importData, $errorTransfers);
    }

    /**
     * @param list<list<string>> $importData
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function assertRequiredHeaders(array $importData, ArrayObject $errorTransfers): ArrayObject
    {
        /** @var list<string> $headers */
        $headers = current($importData);
        $expectedHeaders = $this->merchantCommissionGuiConfig->getCsvFileRequiredColumnsList();

        $missedHeaders = array_diff($expectedHeaders, $headers);
        if ($missedHeaders === []) {
            return $errorTransfers;
        }

        $errorTransfers->append(
            (new ErrorTransfer())->setMessage(sprintf(
                static::ERROR_MESSAGE_HEADERS_MISSING,
                implode(', ', $missedHeaders),
            )),
        );

        return $errorTransfers;
    }

    /**
     * @param list<list<string>> $importData
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function assertRequiredColumns(array $importData, ArrayObject $errorTransfers): ArrayObject
    {
        /** @var list<string> $headers */
        $headers = current($importData);
        foreach ($importData as $rowNumber => $rowData) {
            if ($rowNumber === 0 || count($headers) !== count($rowData)) {
                continue;
            }

            $rowData = array_combine($headers, $rowData);
            foreach (static::REQUIRED_COLUMNS as $requiredColumn) {
                if (!array_key_exists($requiredColumn, $rowData) || empty($rowData[$requiredColumn])) {
                    $errorTransfers->append(
                        (new ErrorTransfer())->setMessage(sprintf(static::ERROR_MESSAGE_EMPTY_COLUMNS, $requiredColumn)),
                    );
                }
            }
        }

        return $errorTransfers;
    }
}
