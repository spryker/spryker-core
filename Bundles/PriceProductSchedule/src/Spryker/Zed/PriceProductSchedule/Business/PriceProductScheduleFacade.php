<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business;

use Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer;
use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile;

/**
 * @method \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory getFactory()
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface getRepository()
 */
class PriceProductScheduleFacade extends AbstractFacade implements PriceProductScheduleFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function applyScheduledPrices(): void
    {
        $this->getFactory()
            ->createPriceProductScheduleApplier()
            ->applyScheduledPrices();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function cleanAppliedScheduledPrices(int $daysRetained): void
    {
        $this->getFactory()
            ->createPriceProductScheduleCleaner()
            ->cleanAppliedScheduledPrices($daysRetained);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function createPriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer {
        return $this->getFactory()
            ->createPriceProductScheduleListCreator()
            ->createPriceProductScheduleList($priceProductScheduleListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function updatePriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer {
        return $this->getFactory()
            ->createPriceProductScheduleListUpdater()
            ->updatePriceProductScheduleList($priceProductScheduleListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    public function importPriceProductSchedules(
        PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
    ): PriceProductScheduleListImportResponseTransfer {
        return $this->getFactory()
            ->createPriceProductScheduleListImporter()
            ->importPriceProductSchedules($priceProductScheduledListImportRequest);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function findPriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer {
        return $this->getFactory()
            ->createPriceProductScheduleListFinder()
            ->findPriceProductScheduleList($priceProductScheduleListTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile $uploadedFile
     * @param \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer
     */
    public function readPriceProductScheduleImportTransfersFromCsvFile(
        UploadedFile $uploadedFile,
        PriceProductScheduledListImportRequestTransfer $productScheduledListImportRequestTransfer
    ): PriceProductScheduledListImportRequestTransfer {
        return $this->getFactory()
            ->createPriceProductScheduleCsvReader()
            ->readPriceProductScheduleImportTransfersFromCsvFile(
                $uploadedFile,
                $productScheduledListImportRequestTransfer
            );
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile $uploadedFile
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer
     */
    public function validateCsvFile(UploadedFile $uploadedFile): PriceProductScheduleCsvValidationResultTransfer
    {
        return $this->getFactory()
            ->createPriceProductScheduleCsvValidator()
            ->validateCsvFile($uploadedFile);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idPriceProductScheduleList
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesByIdPriceProductScheduleList(int $idPriceProductScheduleList): array
    {
        return $this->getRepository()
            ->findPriceProductSchedulesByIdPriceProductScheduleList($idPriceProductScheduleList);
    }
}
