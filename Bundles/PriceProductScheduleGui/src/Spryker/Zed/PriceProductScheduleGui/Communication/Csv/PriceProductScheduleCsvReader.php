<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Csv;

use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\PriceProductScheduleImportMapperInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PriceProductScheduleCsvReader implements PriceProductScheduleCsvReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface
     */
    protected $csvService;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\PriceProductScheduleImportMapperInterface
     */
    protected $priceProductScheduleImportMapper;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface $csvService
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\PriceProductScheduleImportMapperInterface $priceProductScheduleImportMapper
     */
    public function __construct(
        PriceProductScheduleGuiToUtilCsvServiceInterface $csvService,
        PriceProductScheduleImportMapperInterface $priceProductScheduleImportMapper
    ) {
        $this->csvService = $csvService;
        $this->priceProductScheduleImportMapper = $priceProductScheduleImportMapper;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $importCsv
     * @param \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer
     */
    public function readPriceProductScheduleImportTransfersFromCsvFile(
        UploadedFile $importCsv,
        PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
    ): PriceProductScheduledListImportRequestTransfer {
        $importItems = $this->csvService->readUploadedFile($importCsv);
        $headers = current($importItems);
        unset($importItems[0]);

        foreach ($importItems as $rowNumber => $rowData) {
            if ($this->isRowDataEmpty($rowData)) {
                continue;
            }

            $priceProductScheduleImportTransfer = $this->priceProductScheduleImportMapper
                ->mapPriceProductScheduleRowToPriceProductScheduleImportTransfer(
                    array_combine($headers, $rowData),
                    new PriceProductScheduleImportTransfer()
                );

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
        return empty(array_filter(array_filter($rowData)));
    }
}
