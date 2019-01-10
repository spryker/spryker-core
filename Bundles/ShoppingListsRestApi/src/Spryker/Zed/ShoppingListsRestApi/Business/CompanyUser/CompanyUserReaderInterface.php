<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;

interface CompanyUserReaderInterface
{
    /**
     * @param string $companyUserUuid
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function findCompanyUserByUuidAndCustomerReference(
        string $companyUserUuid,
        string $customerReference
    ): CompanyUserResponseTransfer;
}
