<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\CustomerPasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;

class CustomerPasswordPolicyBlacklist extends AbstractCustomerPasswordPolicy implements CustomerPasswordPolicyInterface
{
    public const PASSWORD_POLICY_ATTRIBUTE_UPPER_CASE_REQUIRED = 'required';

    public const PASSWORD_POLICY_ERROR_BLACK_LIST = 'customer.password.error.black_list';

    /**
     * @var int
     */
    protected $passwordBlackList;

    /**
     * @param string[] $blackList
     */
    public function __construct(array $blackList = [])
    {
        $this->passwordBlackList = $blackList;
    }

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if (in_array($password, $this->passwordBlackList, true)) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_BLACK_LIST);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
