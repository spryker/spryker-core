<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
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
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function createMerchant(MerchantTransfer $merchantTransfer): MerchantTransfer;

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
     * - Uses incoming transfer to update entity fields.
     * - Persists the entity to DB.
     * - Throws MerchantNotFoundException in case a record is not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function updateMerchant(MerchantTransfer $merchantTransfer): MerchantTransfer;

    /**
     * Specification:
     * - Finds a merchant record by ID in DB.
     * - Removes the merchant record.
     *
     * @api
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
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantById(MerchantTransfer $merchantTransfer): MerchantTransfer;

    /**
     * Specification:
     * - Finds a merchant by merchant id in provided transfer.
     * - Will fail in case id_merchant is not set on the MerchantTransfer.
     * - Returns MerchantTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantById(MerchantTransfer $merchantTransfer): ?MerchantTransfer;

    /**
     * Specification:
     * - Finds a merchant by email in provided transfer.
     * - Will fail in case email is not set on the MerchantTransfer.
     * - Returns MerchantTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByEmail(MerchantTransfer $merchantTransfer): ?MerchantTransfer;

    /**
     * Specification:
     * - Retrieves collection of all merchants.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchants(): MerchantCollectionTransfer;

    /**
     * Specification:
     * - Creates a new merchant address entity.
     * - Uses incoming transfer to set entity fields.
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function createMerchantAddress(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer;

    /**
     * Specification:
     * - Finds a merchant address by merchant address id in provided transfer.
     * - Will fail in case id_merchant_address is not set on the MerchantAddressTransfer.
     * - Returns MerchantAddressTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer|null
     */
    public function findMerchantAddressById(MerchantAddressTransfer $merchantAddressTransfer): ?MerchantAddressTransfer;

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
    public function getNextStatuses(string $currentStatus): array;
}
