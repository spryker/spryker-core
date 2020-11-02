<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

interface CustomerPasswordPolicyInterface
{
    /**
     * @param \Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy\CustomerPasswordPolicyInterface $customerPasswordPolicy
     *
     * @return self
     */
    public function addPolicy(CustomerPasswordPolicyInterface $customerPasswordPolicy): self;

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer;
}
