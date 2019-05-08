<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Importer;

use DateTime;
use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\Map\PriceProductScheduleImportMapInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Mapper\PriceProductScheduleImportMapperInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Service\PriceProductScheduleGuiToUtilCsvServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PriceProductScheduleImporter implements PriceProductScheduleImporterInterface
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
    public function importPriceProductScheduleImportTransfersFromCsvFile(
        UploadedFile $importCsv,
        PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
    ): PriceProductScheduledListImportRequestTransfer {
        $importItems = $this->csvService->readUploadedFile($importCsv);
        $headers = current($importItems);
        unset($importItems[0]);

        foreach ($importItems as $importItem) {
            $priceProductScheduleImportTransfer = $this->priceProductScheduleImportMapper
                ->mapArrayToPriceProductScheduleTransfer(
                    array_combine($headers, $importItem),
                    new PriceProductScheduleImportTransfer(),
                    PriceProductScheduleImportMapInterface::MAP);

            $priceProductScheduleImportTransfer->setGrossAmount((int)$priceProductScheduleImportTransfer->getGrossAmount());
            $priceProductScheduleImportTransfer->setNetAmount((int)$priceProductScheduleImportTransfer->getNetAmount());

            $productScheduledListImportRequestTransfer->addItem($priceProductScheduleImportTransfer);
        }

        return $productScheduledListImportRequestTransfer;
    }
}
