<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer\Checker;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;

interface PasswordResetExpirationCheckerInterface
{
    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer$customerEntity
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function checkPasswordResetExpiration(
        SpyCustomer $customerEntity,
        CustomerResponseTransfer $customerResponseTransfer
    ): CustomerResponseTransfer;
}
