<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantUserFacadeInterface
{
    /**
     * Specification:
     * - Creates new or finds an existing user based on merchant contact person.
     * - Creates a new merchant user entity.
     * - Persists the entity to DB.
     * - Returns MerchantResponseTransfer.isSuccessful = true.
     * - Returns MerchantResponseTransfer.isSuccessful = false when user is already assigned to another merchant (configurable)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostCreate(MerchantTransfer $merchantTransfer): MerchantResponseTransfer;

    /**
     * Specification:
     * - Updates user according to merchant personal data and merchant email.
     * - Returns MerchantResponseTransfer.isSuccessful = true.
     * - Returns MerchantResponseTransfer.isSuccessful = false when user is already assigned to another merchant (configurable)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $originalMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function handleMerchantPostUpdate(MerchantTransfer $originalMerchantTransfer, MerchantTransfer $updatedMerchantTransfer): MerchantResponseTransfer;
}
