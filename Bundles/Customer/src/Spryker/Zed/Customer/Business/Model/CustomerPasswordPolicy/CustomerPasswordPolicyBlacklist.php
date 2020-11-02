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

    public const PASSWORD_POLICY_ERROR_UPPER_CASE = 'customer.password.error.upper_case';

    public const PASSWORD_POLICY_CHARSET_UPPER_CASE = '\p{Lu}+';

    /**
     * @param string $password
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validatePassword(string $password, CustomerResponseTransfer $customerResponseTransfer): CustomerResponseTransfer
    {
        if (empty($this->config)) {
            return $this->proceed($password, $customerResponseTransfer);
        }

        $upperCaseRequired = $this->config[static::PASSWORD_POLICY_ATTRIBUTE_UPPER_CASE_REQUIRED] ?? false;
        if ($upperCaseRequired && preg_match(static::PASSWORD_POLICY_CHARSET_UPPER_CASE, $password) == false) {
            $this->addError($customerResponseTransfer, static::PASSWORD_POLICY_ERROR_UPPER_CASE);
        }

        return $this->proceed($password, $customerResponseTransfer);
    }
}
