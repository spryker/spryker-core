<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserResponseTransfer;

interface MerchantUserFacadeInterface
{
    /**
     * Specification:
     * - Creates a new merchant user entity.
     * - Persists the entity to DB.
     * - Creates/updates user according to merchant contact person data
     * - Returns MerchantUserResponseTransfer.
     * - Throws an exception if user is already connected to another merchant
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @throws \Spryker\Zed\MerchantUser\Business\Exception\UserAlreadyHasMerchantException
     *
     * @return \Generated\Shared\Transfer\MerchantUserResponseTransfer
     */
    public function createMerchantUserByMerchant(MerchantTransfer $merchantTransfer): MerchantUserResponseTransfer;
}
