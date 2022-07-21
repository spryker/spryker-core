<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Updater;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerAddressUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function updateCustomerAddresses(CustomerTransfer $customerTransfer): void;
}
