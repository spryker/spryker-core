<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

/**
 * @method \Spryker\Zed\Merchant\Business\MerchantBusinessFactory getFactory()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface getEntityManager()
 */
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
     *   - address
     * - Uses incoming transfer to set entity fields.
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
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
     *   - address
     * - Returns MerchantResponseTransfer with 'isSuccessful=false' and error messages if merchant not found.
     * - Returns MerchantResponseTransfer with 'isSuccessful=false' and error messages if merchant status transition is not valid.
     * - Uses incoming transfer to update entity fields.
     * - Persists the entity to DB.
     * - Returns MerchantResponseTransfer with 'isSuccessful=true' and updated MerchantTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function updateMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * Specification:
     * - Finds a merchant record by ID in DB.
     * - Removes the merchant record.
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function deleteMerchant(MerchantTransfer $merchantTransfer): void;

    /**
     * Specification:
     * - Returns a MerchantTransfer by merchant id in provided transfer.
     * - Throws an exception in case a record is not found.
     *
     * @api
     *
     * @deprecated Use MerchantFacade::findOne() instead.
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantById(MerchantTransfer $merchantTransfer): MerchantTransfer;

    /**
     * Specification:
     * - Retrieves collection of all merchants.
     *
     * @api
     *
     * @deprecated Use MerchantFacade::find() instead.
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchants(): MerchantCollectionTransfer;

    /**
     * Specification:
     * - Returns collection of merchants by provided criteria.
     * - Pagination and ordering options can be passed to criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer|null $merchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function find(?MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer = null): MerchantCollectionTransfer;

    /**
     * Specification:
     * - Returns merchant which can filtered by merchant id and email.
     * - Returns MerchantTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findOne(MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer): ?MerchantTransfer;

    /**
     * Specification:
     * - Finds a merchant by merchant id.
     * - Returns MerchantTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByIdMerchant(int $idMerchant): ?MerchantTransfer;

    /**
     * Specification:
     * - Retrieves collection of all merchants.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchantCollection(): MerchantCollectionTransfer;

    /**
     * Specification:
     * - Gets the available merchant statuses for the current merchant status.
     *
     * @api
     *
     * @param string $currentStatus
     *
     * @return string[]
     */
    public function getApplicableMerchantStatuses(string $currentStatus): array;
}
