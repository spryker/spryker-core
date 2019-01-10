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
     * @param string $companyUserUuid
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomerByCompanyUserUuidAndCustomerReference(
        string $companyUserUuid,
        string $customerReference
    ): CustomerResponseTransfer;
}
