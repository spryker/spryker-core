<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Business;

use Generated\Shared\Transfer\CustomerTransfer;

interface BusinessOnBehalfFacadeInterface
{
    /**
     * Specification:
     *
     * - Sets IsOnBehalf property as true when the provided customer has multiple company users connected.
     * - Sets IsOnBehalf property as false otherwise.
     * - Uses provided customer ID to find company users.
     * - Ignores Company user/Customer activity flags
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerWithIsOnBehalf(CustomerTransfer $customerTransfer): CustomerTransfer;
}
