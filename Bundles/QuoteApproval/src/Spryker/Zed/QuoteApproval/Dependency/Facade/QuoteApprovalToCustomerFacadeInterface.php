<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Dependency\Facade;

use Generated\Shared\Transfer\CustomerResponseTransfer;

interface QuoteApprovalToCustomerFacadeInterface
{
    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomerByReference(string $customerReference): CustomerResponseTransfer;
}
