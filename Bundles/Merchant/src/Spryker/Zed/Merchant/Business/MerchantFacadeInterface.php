<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantFacadeInterface
{
    /**
     * Specification:
     * - Creates a new merchant entity.
     * - Requires the following data set on the MerchantTransfer:
     *   - name
     *   - registrationNumber
     *   - contactPersonTitle
     *   - contactPersonFirstName
     *   - contactPersonLastName
     *   - contactPersonPhone
     *   - email
     *   - storeRelation
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
     * - Calls a stack of `MerchantPostCreatePluginInterface` after merchant is created.
     * - Returns MerchantResponseTransfer.isSuccessful=false and error messages if merchant status transition is not valid.
     * - Returns MerchantResponseTransfer.isSuccessful=true and MerchantResponseTransfer.merchant.idMerchant is set from newly created entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * Specification:
     * - Finds a merchant record by ID in DB.
     * - Requires the following data set on the MerchantTransfer:
     *   - idMerchant
     *   - name
     *   - registrationNumber
     *   - contactPersonTitle
     *   - contactPersonFirstName
     *   - contactPersonLastName
     *   - contactPersonPhone
     *   - email
     *   - storeRelation
     * - Calls a stack of `MerchantPostUpdatePluginInterface` after merchant is updated.
     * - Returns MerchantResponseTransfer.isSuccessful=false and error messages if merchant not found.
     * - Returns MerchantResponseTransfer.isSuccessful=false and error messages if merchant status transition is not valid.
     * - Persists the entity to DB.
     * - Returns MerchantResponseTransfer.isSuccessful=true and updated MerchantTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function updateMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * Specification:
     * - Returns collection of merchants by provided criteria.
     * - Pagination, filter and ordering options can be passed to criteria.
     * - Pagination is controlled with page, maxPerPage, nbResults, previousPage, nextPage, firstIndex, lastIndex, firstPage and lastPage values.
     * - Filter supports ordering by field.
     * - Default order by merchant name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function get(MerchantCriteriaTransfer $merchantCriteriaTransfer): MerchantCollectionTransfer;

    /**
     * Specification:
     * - Returns merchant which can filtered by merchant id and email.
     * - Returns MerchantTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaTransfer $merchantCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findOne(MerchantCriteriaTransfer $merchantCriteriaTransfer): ?MerchantTransfer;

    /**
     * Specification:
     * - Gets the available merchant statuses for the current merchant status.
     * - Returns empty array if no available statuses exist.
     *
     * @api
     *
     * @param string $currentStatus
     *
     * @return string[]
     */
    public function getApplicableMerchantStatuses(string $currentStatus): array;
}
