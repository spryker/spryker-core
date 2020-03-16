<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantUserFacadeInterface
{
    /**
     * Specification:
     * - Creates a new merchant user entity.
     * - Persists the entity to DB.
     * - Returns merchant user response with newly created merchant user transfer inside.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function create(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer;

    /**
     * Specification:
     * - Updates MerchantUser with passed MerchantUser transfer data.
     * - Returns MerchantUserResponse transfer with updated MerchantUser transfer inside.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function update(MerchantUserTransfer $merchantUserTransfer): MerchantUserResponseTransfer;

    /**
     * Specification:
     * - Disables MerchantUsers that related to given criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return void
     */
    public function disableMerchantUsers(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): void;

    /**
     * Specification:
     * - Searching for MerchantUser by given criteria
     * - Returns MerchantUser transfer or null if MerchantUser was't found
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findOne(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): ?MerchantUserTransfer;
}
