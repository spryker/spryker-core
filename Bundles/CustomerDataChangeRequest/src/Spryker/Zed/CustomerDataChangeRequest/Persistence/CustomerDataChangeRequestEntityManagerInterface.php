<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest\Persistence;

use Generated\Shared\Transfer\CustomerDataChangeRequestTransfer;

interface CustomerDataChangeRequestEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerDataChangeRequestTransfer
     */
    public function saveEmailCustomerDataChangeRequest(CustomerDataChangeRequestTransfer $customerDataChangeRequestTransfer): CustomerDataChangeRequestTransfer;
}
