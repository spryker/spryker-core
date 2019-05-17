<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\BusinessOnBehalf\Checker;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isCustomerChangeAllowed(CustomerTransfer $customerTransfer): bool;
}
