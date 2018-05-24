<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

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
     * - Retrieves collection of all merchants
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchants(): MerchantCollectionTransfer;
}
