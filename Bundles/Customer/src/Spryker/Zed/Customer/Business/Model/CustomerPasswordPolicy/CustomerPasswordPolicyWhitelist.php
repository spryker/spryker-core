<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use ArrayObject;
use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicyWhitelist extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    /**
     * @var string[]
     */
    protected $passwordWhiteList;

    /**
     * @param string[] $passwordWhiteList
     */
    public function __construct(array $passwordWhiteList = [])
    {
        $this->passwordWhiteList = $passwordWhiteList;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if (in_array($password, $this->passwordWhiteList, true)) {
            return $customerResponseTransfer->setIsSuccess(true)->setErrors(new ArrayObject());
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
