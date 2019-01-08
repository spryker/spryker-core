<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\Customer;

use Generated\Shared\Transfer\CustomerResponseTransfer;

interface CustomerReaderInterface
{
    /**
     * @param string $customerReference
     * @param string $companyUserUuid
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomerByCustomerReferenceAndCompanyUserUuid(
        string $customerReference,
        string $companyUserUuid
    ): CustomerResponseTransfer;
}
