<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface MerchantUserFacadeInterface
{
    /**
     * Specification:
     * - Creates new or finds an existing user based on merchant contact person.
     * - Creates a new merchant user entity.
     * - Persists the entity to DB.
     * - Returns MerchantUserResponseTransfer.isSuccessful = true.
     * - Returns MerchantUserResponseTransfer.isSuccessful = false when user is already assigned to another merchant (configurable)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createByMerchant(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer;

    /**
     * Specification:
     * - Updates user according to merchant personal data and merchant email.
     * - Returns MerchantUserResponseTransfer.isSuccessful = true.
     * - Returns MerchantUserResponseTransfer.isSuccessful = false if merchant user doesn't exist
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateUserByMerchant(MerchantUserTransfer $merchantUserTransfer, MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer;

    /**
     * Specification:
     * - Returns a merchant user transfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer): ?MerchantUserTransfer;
}
