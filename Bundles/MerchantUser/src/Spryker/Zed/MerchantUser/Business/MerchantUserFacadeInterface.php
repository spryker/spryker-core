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
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createMerchantAdmin(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer;

    /**
     * Specification:
     * - Updates user according to merchant personal data and merchant email.
     * - Returns MerchantResponseTransfer.isSuccessful = true.
     * - Returns MerchantResponseTransfer.isSuccessful = false when user is already assigned to another merchant (configurable)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function updateMerchantAdmin(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer;

    /**
     * Specification:
     * - Finds MerchantUser by provided criteria filter transfer.
     * - Returns MerchantUserTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer): ?MerchantUserTransfer;

    /**
     * Specification:
     * - Returns effective merchant user.
     * - Should not be used when user is not logged in.
     * - Throws MerchantUserNotFoundException exception if no Merchant users exist for current logged in user.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer
     */
    public function getCurrentMerchantUser(): MerchantUserTransfer;
}
