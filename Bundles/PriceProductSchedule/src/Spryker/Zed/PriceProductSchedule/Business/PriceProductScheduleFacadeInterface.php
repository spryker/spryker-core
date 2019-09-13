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
use Generated\Shared\Transfer\PriceProductScheduleResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile;

interface PriceProductScheduleFacadeInterface
{
    /**
     * Specification:
     * - Applies scheduled prices for current store.
     * - Persists price product store for applied scheduled price product.
     * - Disables not relevant price product schedules for applied scheduled price products.
     * - Reverts price products from the fallback price types for scheduled product prices that are finished.
     *
     * @api
     *
     * @return void
     */
    public function applyScheduledPrices(): void;

    /**
     * Specification:
     * - Deletes scheduled prices that has been ended earlier than the days provided as parameter.
     *
     * @api
     *
     * @param int $daysRetained
     *
     * @return void
     */
    public function cleanAppliedScheduledPrices(int $daysRetained): void;

    /**
     * Specification:
     * - Creates and saves price product schedule list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function createPriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer;

    /**
     * Specification:
     * - Updates and saves price product schedule list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function updatePriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer;

    /**
     * Specification:
     * - Validates import data.
     * - Adds validation errors to response transfer.
     * - Expands with relevant price product data.
     * - Saves price product schedules.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    public function importPriceProductSchedules(
        PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
    ): PriceProductScheduleListImportResponseTransfer;

    /**
     * Specification:
     * - Finds price product schedule list by idPriceProductScheduleList.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function findPriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer;

    /**
     * Specification:
     * - Reads uploaded file.
     * - Maps rows from the file to the transfers.
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
    ): PriceProductScheduledListImportRequestTransfer;

    /**
     * Specification:
     * - Reads uploaded file.
     * - Validates data from the file.
     *
     * @api
     *
     * @param \Spryker\Zed\PriceProductSchedule\Communication\File\UploadedFile $uploadedFile
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleCsvValidationResultTransfer
     */
    public function validateCsvFile(UploadedFile $uploadedFile): PriceProductScheduleCsvValidationResultTransfer;

    /**
     * Specification:
     * - Finds all scheduled prices related to given scheduled price list.
     *
     * @api
     *
     * @param int $idPriceProductScheduleList
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesByIdPriceProductScheduleList(int $idPriceProductScheduleList): array;

    /**
     * - Creates scheduled price.
     * - Apply scheduled prices for product, related to given scheduled price.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    public function createAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer;

    /**
     * Specification:
     * - Removes scheduled price with given id.
     * - Applies scheduled price for product, related to given scheduled price.
     *
     * @api
     *
     * @param int $idPriceProductSchedule
     *
     * @return void
     */
    public function removeAndApplyPriceProductSchedule(int $idPriceProductSchedule): void;

    /**
     * Specification:
     * - Updates given scheduled price.
     * - Applies scheduled price to product, related to given scheduled price.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    public function updateAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer;

    /**
     * Specification:
     * - Finds scheduled price by given id.
     * - Returns PriceProductScheduleTransfer or null if there are no records in database.
     *
     * @api
     *
     * @param int $idPriceProductSchedule
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer|null
     */
    public function findPriceProductScheduleById(int $idPriceProductSchedule): ?PriceProductScheduleTransfer;

    /**
     * Specification:
     * - Checks uniqueness of given scheduled price.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return bool
     */
    public function isPriceProductScheduleUnique(PriceProductScheduleTransfer $priceProductScheduleTransfer): bool;
}
