<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile;
use Spryker\Zed\PriceProductSchedule\Dependency\Service\PriceProductScheduleToUtilCsvServiceInterface;

class PriceProductScheduleCsvReader implements PriceProductScheduleCsvReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Service\PriceProductScheduleToUtilCsvServiceInterface
     */
    protected $csvService;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleImportMapperInterface
     */
    protected $priceProductScheduleImportMapper;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Service\PriceProductScheduleToUtilCsvServiceInterface $csvService
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleImportMapperInterface $priceProductScheduleImportMapper
     */
    public function __construct(
        PriceProductScheduleToUtilCsvServiceInterface $csvService,
        PriceProductScheduleImportMapperInterface $priceProductScheduleImportMapper
    ) {
        $this->csvService = $csvService;
        $this->priceProductScheduleImportMapper = $priceProductScheduleImportMapper;
    }

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile $uploadedFile
     * @param \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer
     */
    public function readPriceProductScheduleImportTransfersFromCsvFile(
        UploadedFile $uploadedFile,
        PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
    ): PriceProductScheduledListImportRequestTransfer {
        $importData = $this->csvService->readUploadedFile($uploadedFile);
        $headers = current($importData);
        $importData = $this->removeHeadersFromImportData($importData);

        foreach ($importData as $rowNumber => $rowData) {
            if ($this->isRowDataEmpty($rowData)) {
                continue;
            }

            $priceProductScheduleImportTransfer = $this->priceProductScheduleImportMapper
                ->mapPriceProductScheduleRowToPriceProductScheduleImportTransfer(
                    array_combine($headers, $rowData),
                    new PriceProductScheduleImportTransfer()
                );
            $priceProductScheduleImportTransfer->requireMetaData();
            $priceProductScheduleImportTransfer->getMetaData()->setIdentifier($rowNumber);

            $productScheduledListImportRequestTransfer->addItem($priceProductScheduleImportTransfer);
        }

        return $productScheduledListImportRequestTransfer;
    }

    /**
     * @param array $rowData
     *
     * @return bool
     */
    protected function isRowDataEmpty(array $rowData): bool
    {
        $clearedRowData = $this->clearRowDataFromEmptyValues($rowData);

        return empty($clearedRowData);
    }

    /**
     * @param array $rowData
     *
     * @return array
     */
    protected function clearRowDataFromEmptyValues(array $rowData): array
    {
        return array_filter($rowData);
    }

    /**
     * @param array $importData
     *
     * @return array
     */
    protected function removeHeadersFromImportData(array $importData): array
    {
        unset($importData[0]);

        return $importData;
    }
}
